<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Restaurant;
use App\Models\Product;
use App\Models\Category;
use App\Models\GlobalSupplement;

class MenuController extends Controller
{
    public function index(): void
    {
        $restaurantModel = new Restaurant();
        $restaurants = $restaurantModel->getActive();

        // Default to first restaurant
        $this->byRestaurant((string)($restaurants[0]['id'] ?? 1));
    }

    public function byRestaurant(string $restaurantId): void
    {
        $rid = (int)$restaurantId;
        $restaurantModel = new Restaurant();
        $productModel = new Product();
        $categoryModel = new Category();

        $restaurant = $restaurantModel->findById($rid);
        if (!$restaurant) {
            $this->redirect('/');
            return;
        }

        $restaurants = $restaurantModel->getActive();
        $categories = $categoryModel->getActive();
        $products = $productModel->getMenuByRestaurant($rid);

        // Group products by category
        $menuByCategory = [];
        foreach ($products as $product) {
            $catSlug = $product['category_slug'];
            if (!isset($menuByCategory[$catSlug])) {
                $menuByCategory[$catSlug] = [
                    'name'     => $product['category_name'],
                    'slug'     => $catSlug,
                    'products' => [],
                ];
            }
            $menuByCategory[$catSlug]['products'][] = $product;
        }

        $this->layout('menu.index', [
            'restaurant'     => $restaurant,
            'restaurants'    => $restaurants,
            'categories'     => $categories,
            'menuByCategory' => $menuByCategory,
            'pageTitle'      => 'Menu - ' . $restaurant['name'],
        ]);
    }

    public function show(string $id): void
    {
        $productModel = new Product();
        $product = $productModel->getWithDetails((int)$id);

        if (!$product) {
            $this->redirect('/menu');
            return;
        }

        // Generate virtual viande options from product prices
        if (isset($product['price_double']) && $product['price_double'] > 0) {
            $viandeOptions = [
                [
                    'id' => 'simple',
                    'name' => 'Simple (1 viande)',
                    'option_group' => 'viande',
                    'option_type' => 'radio',
                    'price_modifier' => 0,
                    'product_id' => $product['id']
                ],
                [
                    'id' => 'double',
                    'name' => 'Double (2 viandes)',
                    'option_group' => 'viande',
                    'option_type' => 'radio',
                    'price_modifier' => (float)$product['price_double'] - (float)$product['price'],
                    'product_id' => $product['id']
                ]
            ];
            
            // Prepend viande options to existing options
            $product['options'] = array_merge($viandeOptions, $product['options'] ?? []);
        }

        // Load global supplements for this product
        $supplementModel = new GlobalSupplement();
        $supplements = $supplementModel->getByProduct((int)$id);
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

        $this->layout('menu.product', [
            'product'   => $product,
            'pageTitle' => $product['name'] . " - Billy's",
        ]);
    }
}
