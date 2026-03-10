<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Order;

class ApiOrderController extends Controller
{
    public function place(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) $input = $_POST;

        $cart = Session::get('cart', []);
        $restaurantId = Session::get('cart_restaurant_id');

        if (empty($cart) || !$restaurantId) {
            $this->json(['success' => false, 'message' => 'Votre panier est vide.'], 400);
            return;
        }

        $user = Auth::user();

        $customerName = $this->sanitize($input['customer_name'] ?? $user['name'] ?? '');
        $customerPhone = $this->sanitize($input['customer_phone'] ?? '');
        $customerEmail = $this->sanitize($input['customer_email'] ?? $user['email'] ?? '');
        $notes = $this->sanitize($input['notes'] ?? '');

        if (empty($customerName)) {
            $this->json(['success' => false, 'message' => 'Nom requis.'], 400);
            return;
        }

        $orderModel = new Order();

        $items = [];
        foreach ($cart as $cartItem) {
            $lineTotal = ($cartItem['price'] + $cartItem['options_price']) * $cartItem['quantity'];
            $items[] = [
                'product_id'   => $cartItem['product_id'],
                'product_name' => $cartItem['name'],
                'quantity'     => $cartItem['quantity'],
                'unit_price'   => $cartItem['price'],
                'options'      => $cartItem['option_names'] ?? [],
                'options_price' => $cartItem['options_price'],
                'line_total'   => $lineTotal,
            ];
        }

        try {
            $orderId = $orderModel->createOrder([
                'user_id'        => Auth::id(),
                'restaurant_id'  => $restaurantId,
                'customer_name'  => $customerName,
                'customer_phone' => $customerPhone,
                'customer_email' => $customerEmail,
                'notes'          => $notes,
            ], $items);

            // Clear cart
            Session::remove('cart');
            Session::remove('cart_restaurant_id');

            $order = $orderModel->findById($orderId);

            $this->json([
                'success'      => true,
                'message'      => 'Commande passée avec succès !',
                'order_number' => $order['order_number'],
                'order_id'     => $orderId,
            ]);
        } catch (\Exception $e) {
            $this->json(['success' => false, 'message' => 'Erreur lors de la commande. Veuillez réessayer.'], 500);
        }
    }
}
