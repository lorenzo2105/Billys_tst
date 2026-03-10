<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Order;

class ApiKitchenController extends Controller
{
    public function orders(): void
    {
        $restaurantId = isset($_GET['restaurant_id']) ? (int)$_GET['restaurant_id'] : null;

        $orderModel = new Order();
        $orders = $orderModel->getActiveOrders($restaurantId);

        // Fetch items for each order
        foreach ($orders as &$order) {
            $order['items'] = \App\Core\Database::getInstance()->fetchAll(
                "SELECT * FROM order_items WHERE order_id = :oid",
                ['oid' => $order['id']]
            );
        }

        $this->json([
            'success' => true,
            'orders'  => $orders,
            'count'   => count($orders),
        ]);
    }

    public function updateStatus(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) $input = $_POST;

        $orderId = (int)($input['order_id'] ?? 0);
        $status = $input['status'] ?? '';

        $validStatuses = ['new', 'preparing', 'ready', 'completed', 'cancelled'];
        if (!$orderId || !in_array($status, $validStatuses, true)) {
            $this->json(['success' => false, 'message' => 'Données invalides.'], 400);
            return;
        }

        $orderModel = new Order();
        $order = $orderModel->findById($orderId);

        if (!$order) {
            $this->json(['success' => false, 'message' => 'Commande introuvable.'], 404);
            return;
        }

        $orderModel->updateStatus($orderId, $status, Auth::id());

        $this->json([
            'success' => true,
            'message' => 'Statut mis à jour.',
            'order_id' => $orderId,
            'new_status' => $status,
        ]);
    }
}
