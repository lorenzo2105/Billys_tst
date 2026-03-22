<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    protected string $table = 'products';

    public function getByCategory(int $categoryId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table}
             WHERE category_id = :cid AND status = 'available'
             ORDER BY sort_order ASC",
            ['cid' => $categoryId]
        );
    }

    public function getMenuByRestaurant(int $restaurantId): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.name as category_name, c.slug as category_slug, c.sort_order as cat_order,
                    pr.is_available, pr.stock_status,
                    (SELECT COUNT(*) FROM product_options po WHERE po.product_id = p.id AND po.is_active = 1) as options_count
             FROM {$this->table} p
             JOIN categories c ON c.id = p.category_id AND c.is_active = 1
             LEFT JOIN product_restaurant pr ON pr.product_id = p.id AND pr.restaurant_id = :rid
             WHERE p.status != 'unavailable'
             ORDER BY c.sort_order ASC, p.sort_order ASC",
            ['rid' => $restaurantId]
        );
    }

    public function getFeatured(): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.name as category_name
             FROM {$this->table} p
             JOIN categories c ON c.id = p.category_id
             WHERE p.is_featured = 1 AND p.status = 'available'
             ORDER BY p.sort_order ASC
             LIMIT 8"
        );
    }

    public function getWithDetails(int $id): ?array
    {
        $product = $this->findById($id);
        if (!$product) return null;

        $product['options'] = $this->db->fetchAll(
            "SELECT * FROM product_options WHERE product_id = :pid AND is_active = 1",
            ['pid' => $id]
        );

        $product['category'] = $this->db->fetch(
            "SELECT * FROM categories WHERE id = :cid",
            ['cid' => $product['category_id']]
        );

        return $product;
    }

    public function getAllWithCategory(): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.name as category_name
             FROM {$this->table} p
             JOIN categories c ON c.id = p.category_id
             ORDER BY c.sort_order ASC, p.sort_order ASC"
        );
    }

    public function toggleStatus(int $id): void
    {
        $product = $this->findById($id);
        if (!$product) return;

        $newStatus = $product['status'] === 'available' ? 'unavailable' : 'available';
        $this->update($id, ['status' => $newStatus]);
    }

    public function updateAvailability(int $productId, int $restaurantId, bool $available, string $stockStatus = 'in_stock'): void
    {
        $this->db->execute(
            "INSERT INTO product_restaurant (product_id, restaurant_id, is_available, stock_status)
             VALUES (:pid, :rid, :avail, :stock)
             ON DUPLICATE KEY UPDATE is_available = :avail2, stock_status = :stock2",
            [
                'pid' => $productId,
                'rid' => $restaurantId,
                'avail' => $available ? 1 : 0,
                'stock' => $stockStatus,
                'avail2' => $available ? 1 : 0,
                'stock2' => $stockStatus,
            ]
        );
    }

    public function getAvailabilityPerRestaurant(int $productId): array
    {
        $rows = $this->db->fetchAll(
            "SELECT r.id as restaurant_id, r.name as restaurant_name,
                    COALESCE(pr.is_available, 1) as is_available,
                    COALESCE(pr.stock_status, 'in_stock') as stock_status
             FROM restaurants r
             LEFT JOIN product_restaurant pr ON pr.product_id = :pid AND pr.restaurant_id = r.id
             WHERE r.is_active = 1
             ORDER BY r.name ASC",
            ['pid' => $productId]
        );
        $result = [];
        foreach ($rows as $row) {
            $result[$row['restaurant_id']] = $row;
        }
        return $result;
    }
}
