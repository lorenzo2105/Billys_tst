<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Restaurant;

class CartController extends Controller
{
    public function index(): void
    {
        $restaurantModel = new Restaurant();
        $restaurants = $restaurantModel->getActive();

        $this->layout('cart.index', [
            'restaurants' => $restaurants,
            'pageTitle'   => 'Mon Panier',
        ]);
    }
}
