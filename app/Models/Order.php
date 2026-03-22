<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Order extends Model
{
    protected string $table = 'orders';

    public function generateOrderNumber(): string
    {
        return 'BF-' . strtoupper(substr(uniqid(), -6)) . '-' . date('dHi');
    }

    public function createOrder(array $data, array $items): int
    {
        $db = $this->db->getConnection();
        $db->beginTransaction();

        try {
            $orderNumber = $this->generateOrderNumber();

            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['line_total'];
            }
            $tax = round($subtotal * 0.10, 2);
            $total = $subtotal + $tax;

            $orderId = (int)$this->create([
                'user_id'        => $data['user_id'] ?? null,
                'restaurant_id'  => $data['restaurant_id'],
                'order_number'   => $orderNumber,
                'customer_name'  => $data['customer_name'],
                'customer_phone' => $data['customer_phone'] ?? null,
                'customer_email' => $data['customer_email'] ?? null,
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'total'          => $total,
                'status'         => 'new',
                'notes'          => $data['notes'] ?? null,
            ]);

            foreach ($items as $item) {
                $this->db->insert(
                    "INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, options_json, options_price, line_total)
                     VALUES (:oid, :pid, :pname, :qty, :price, :opts, :opts_price, :total)",
                    [
                        'oid'        => $orderId,
                        'pid'        => $item['product_id'],
                        'pname'      => $item['product_name'],
                        'qty'        => $item['quantity'],
                        'price'      => $item['unit_price'],
                        'opts'       => json_encode($item['options'] ?? []),
                        'opts_price' => $item['options_price'] ?? 0,
                        'total'      => $item['line_total'],
                    ]
                );
            }

            // Add status history
            $this->db->insert(
                "INSERT INTO order_status_history (order_id, status, changed_by) VALUES (:oid, 'new', :uid)",
                ['oid' => $orderId, 'uid' => $data['user_id'] ?? null]
            );

            $db->commit();
            return $orderId;

        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function getWithItems(int $id): ?array
    {
        $order = $this->findById($id);
        if (!$order) return null;

        $order['items'] = $this->db->fetchAll(
            "SELECT * FROM order_items WHERE order_id = :oid",
            ['oid' => $id]
        );

        $order['restaurant'] = $this->db->fetch(
            "SELECT * FROM restaurants WHERE id = :rid",
            ['rid' => $order['restaurant_id']]
        );

        $order['status_history'] = $this->db->fetchAll(
            "SELECT osh.*, u.name as changed_by_name
             FROM order_status_history osh
             LEFT JOIN users u ON u.id = osh.changed_by
             WHERE osh.order_id = :oid
             ORDER BY osh.created_at ASC",
            ['oid' => $id]
        );

        return $order;
    }

    public function getByUser(int $userId): array
    {
        return $this->db->fetchAll(
            "SELECT o.*, r.name as restaurant_name,
                    (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
             FROM {$this->table} o
             JOIN restaurants r ON r.id = o.restaurant_id
             WHERE o.user_id = :uid
             ORDER BY o.created_at DESC",
            ['uid' => $userId]
        );
    }

    public function getByRestaurant(int $restaurantId, ?string $status = null): array
    {
        $sql = "SELECT o.*, u.name as user_name,
                       (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
                FROM {$this->table} o
                LEFT JOIN users u ON u.id = o.user_id
                WHERE o.restaurant_id = :rid";
        $params = ['rid' => $restaurantId];

        if ($status) {
            $sql .= " AND o.status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY o.created_at DESC";

        return $this->db->fetchAll($sql, $params);
    }

    public function getActiveOrders(?int $restaurantId = null): array
    {
        $sql = "SELECT o.*, r.name as restaurant_name,
                       (SELECT GROUP_CONCAT(CONCAT(oi.quantity, 'x ', oi.product_name) SEPARATOR ', ')
                        FROM order_items oi WHERE oi.order_id = o.id) as items_summary
                FROM {$this->table} o
                JOIN restaurants r ON r.id = o.restaurant_id
                WHERE o.status IN ('new', 'preparing', 'ready')";
        $params = [];

        if ($restaurantId) {
            $sql .= " AND o.restaurant_id = :rid";
            $params['rid'] = $restaurantId;
        }

        $sql .= " ORDER BY FIELD(o.status, 'new', 'preparing', 'ready'), o.created_at ASC";

        return $this->db->fetchAll($sql, $params);
    }

    public function updateStatus(int $orderId, string $status, ?int $changedBy = null): void
    {
        $this->update($orderId, ['status' => $status]);

        $this->db->insert(
            "INSERT INTO order_status_history (order_id, status, changed_by) VALUES (:oid, :status, :uid)",
            ['oid' => $orderId, 'status' => $status, 'uid' => $changedBy]
        );
    }

    public function getAllWithDetails(int $limit = 50, ?int $restaurantId = null): array
    {
        $limit = max(1, min($limit, 500));
        $where = $restaurantId ? "WHERE o.restaurant_id = {$restaurantId}" : '';
        return $this->db->fetchAll(
            "SELECT o.*, r.name as restaurant_name, u.name as user_name,
                    (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
             FROM {$this->table} o
             JOIN restaurants r ON r.id = o.restaurant_id
             LEFT JOIN users u ON u.id = o.user_id
             {$where}
             ORDER BY o.created_at DESC
             LIMIT {$limit}"
        );
    }

    public function getStats(): array
    {
        $today = date('Y-m-d');

        $todayOrders = $this->db->fetch(
            "SELECT COUNT(*) as count, COALESCE(SUM(total), 0) as revenue
             FROM {$this->table} WHERE DATE(created_at) = :today AND status != 'cancelled'",
            ['today' => $today]
        );

        $totalOrders = $this->db->fetch(
            "SELECT COUNT(*) as count, COALESCE(SUM(total), 0) as revenue
             FROM {$this->table} WHERE status != 'cancelled'"
        );

        $pendingOrders = $this->count("status IN ('new', 'preparing')");

        return [
            'today_count'   => (int)($todayOrders['count'] ?? 0),
            'today_revenue' => (float)($todayOrders['revenue'] ?? 0),
            'total_count'   => (int)($totalOrders['count'] ?? 0),
            'total_revenue' => (float)($totalOrders['revenue'] ?? 0),
            'pending_count' => $pendingOrders,
        ];
    }
}
