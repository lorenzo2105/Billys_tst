<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Product;

class ApiCartController extends Controller
{
    public function get(): void
    {
        $cart = Session::get('cart', []);
        $restaurantId = Session::get('cart_restaurant_id');

        $this->json([
            'success'       => true,
            'cart'          => array_values($cart),
            'restaurant_id' => $restaurantId,
            'total'         => $this->calculateTotal($cart),
            'count'         => $this->calculateCount($cart),
        ]);
    }

    public function add(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $productId = (int)($input['product_id'] ?? 0);
        $quantity = max(1, (int)($input['quantity'] ?? 1));
        $restaurantId = (int)($input['restaurant_id'] ?? 0);
        $options = $input['options'] ?? [];

        if (!$productId || !$restaurantId) {
            $this->json(['success' => false, 'message' => 'Données invalides.'], 400);
            return;
        }

        $productModel = new Product();
        $product = $productModel->findById($productId);

        if (!$product || $product['status'] !== 'available') {
            $this->json(['success' => false, 'message' => 'Produit indisponible.'], 400);
            return;
        }

        // Check if cart has items from different restaurant
        $currentRestaurant = Session::get('cart_restaurant_id');
        if ($currentRestaurant && $currentRestaurant !== $restaurantId) {
            // Clear cart if switching restaurants
            Session::set('cart', []);
        }
        Session::set('cart_restaurant_id', $restaurantId);

        $cart = Session::get('cart', []);

        // Calculate options price
        $optionsPrice = 0;
        $optionNames = [];
        if (!empty($options)) {
            $db = \App\Core\Database::getInstance();
            foreach ($options as $optId) {
                // Handle virtual viande/formule options
                if ($optId === 'simple') {
                    $optionNames[] = 'Simple 🥩';
                } elseif ($optId === 'double') {
                    $optionNames[] = 'Double 🥩🥩';
                    $optionsPrice += (float)$product['price_double'] - (float)$product['price'];
                } elseif ($optId === 'burger_seul') {
                    $optionNames[] = 'Burger seul';
                } else {
                    // Real database options
                    $opt = $db->fetch("SELECT * FROM product_options WHERE id = :id", ['id' => (int)$optId]);
                    if ($opt) {
                        $optionsPrice += (float)$opt['price_modifier'];
                        $optionNames[] = $opt['name'];
                    }
                }
            }
        }

        // Create unique cart key based on product + options
        $cartKey = $productId . '-' . implode(',', $options);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'key'           => $cartKey,
                'product_id'    => $productId,
                'name'          => $product['name'],
                'price'         => (float)$product['price'],
                'image'         => $product['image'],
                'quantity'      => $quantity,
                'options'       => $options,
                'option_names'  => $optionNames,
                'options_price' => $optionsPrice,
            ];
        }

        Session::set('cart', $cart);

        $this->json([
            'success' => true,
            'message' => $product['name'] . ' ajouté au panier !',
            'cart'    => array_values($cart),
            'total'   => $this->calculateTotal($cart),
            'count'   => $this->calculateCount($cart),
        ]);
    }

    public function update(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) $input = $_POST;

        $cartKey = $input['cart_key'] ?? '';
        $quantity = (int)($input['quantity'] ?? 0);

        $cart = Session::get('cart', []);

        if (!isset($cart[$cartKey])) {
            $this->json(['success' => false, 'message' => 'Article non trouvé.'], 404);
            return;
        }

        if ($quantity <= 0) {
            unset($cart[$cartKey]);
        } else {
            $cart[$cartKey]['quantity'] = $quantity;
        }

        Session::set('cart', $cart);

        $this->json([
            'success' => true,
            'cart'    => array_values($cart),
            'total'   => $this->calculateTotal($cart),
            'count'   => $this->calculateCount($cart),
        ]);
    }

    public function remove(): void
    {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) $input = $_POST;

        $cartKey = $input['cart_key'] ?? '';
        $cart = Session::get('cart', []);

        unset($cart[$cartKey]);
        Session::set('cart', $cart);

        if (empty($cart)) {
            Session::remove('cart_restaurant_id');
        }

        $this->json([
            'success' => true,
            'cart'    => array_values($cart),
            'total'   => $this->calculateTotal($cart),
            'count'   => $this->calculateCount($cart),
        ]);
    }

    private function calculateTotal(array $cart): float
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += ($item['price'] + $item['options_price']) * $item['quantity'];
        }
        return round($total, 2);
    }

    private function calculateCount(array $cart): int
    {
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }
}
