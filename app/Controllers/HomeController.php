<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Restaurant;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(): void
    {
        $restaurantModel = new Restaurant();
        $productModel = new Product();

        $restaurants = $restaurantModel->getActive();
        $featured = $productModel->getFeatured();

        $this->layout('home.index', [
            'restaurants' => $restaurants,
            'featured'    => $featured,
            'pageTitle'   => "Billy's Fast Food - Accueil",
        ]);
    }
}
