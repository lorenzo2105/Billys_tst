<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Restaurant;

class KitchenController extends Controller
{
    public function index(): void
    {
        $restaurantModel = new Restaurant();
        $restaurants = $restaurantModel->getActive();

        $this->layout('kitchen.index', [
            'restaurants' => $restaurants,
            'pageTitle'   => 'Cuisine - KDS',
        ], 'layouts.kitchen');
    }
}
