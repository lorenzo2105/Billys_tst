<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\GlobalSupplement;

class ApiMenuController extends Controller
{
    public function getMenu(string $restaurantId): void
    {
        $productModel = new Product();
        $categoryModel = new Category();

        $categories = $categoryModel->getActive();
        $products = $productModel->getMenuByRestaurant((int)$restaurantId);

        $menu = [];
        foreach ($categories as $cat) {
            $catProducts = array_filter($products, fn($p) => (int)$p['category_id'] === (int)$cat['id']);
            if (!empty($catProducts)) {
                $menu[] = [
                    'category' => $cat,
                    'products' => array_values($catProducts),
                ];
            }
        }

        $this->json(['success' => true, 'menu' => $menu]);
    }

    public function getProduct(string $id): void
    {
        $productModel = new Product();
        $product = $productModel->getWithDetails((int)$id);

        if (!$product) {
            $this->json(['success' => false, 'message' => 'Produit introuvable.'], 404);
            return;
        }

        // Check if product is a burger
        $categoryModel = new Category();
        $category = $categoryModel->findById((int)$product['category_id']);
        $isBurger = $category && stripos($category['name'], 'burger') !== false;
        
        // Generate virtual viande + taille_menu options for burgers
        if ($isBurger) {
            $viandeOptions = [];
            
            // Only add viande options if price_double is configured
            if (isset($product['price_double']) && $product['price_double'] > 0) {
                $viandeOptions[] = [
                    'id' => 'simple',
                    'name' => 'Simple 🥩',
                    'option_group' => 'viande',
                    'option_type' => 'radio',
                    'price_modifier' => 0,
                    'product_id' => $product['id']
                ];
                
                $viandeOptions[] = [
                    'id' => 'double',
                    'name' => 'Double 🥩🥩',
                    'option_group' => 'viande',
                    'option_type' => 'radio',
                    'price_modifier' => (float)$product['price_double'] - (float)$product['price'],
                    'product_id' => $product['id']
                ];
            }
            
            // Always add "Burger seul" option for burgers
            $burgerSeul = [
                'id'             => 'burger_seul',
                'name'           => 'Burger seul',
                'option_group'   => 'taille_menu',
                'option_type'    => 'radio',
                'price_modifier' => 0,
                'product_id'     => $product['id'],
            ];
            $product['options'] = array_merge($viandeOptions, [$burgerSeul], $product['options'] ?? []);
        }

        // Load global supplements for burgers only
        
        if ($isBurger) {
            $supplementModel = new GlobalSupplement();
            $supplements = $supplementModel->getActive();
            if (!empty($supplements)) {
                foreach ($supplements as $supp) {
                    $product['options'][] = [
                        'id' => $supp['id'],
                        'name' => $supp['name'],
                        'option_group' => 'supplements',
                        'option_type' => 'checkbox',
                        'price_modifier' => (float)$supp['price'],
                        'product_id' => $product['id']
                    ];
                }
            }
        }

        $this->json(['success' => true, 'product' => $product]);
    }
}
