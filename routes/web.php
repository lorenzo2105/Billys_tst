<?php
declare(strict_types=1);

use App\Core\Router;

/** @var Router $router */

// ── Public Routes ──────────────────────────────────────────
$router->get('/', 'HomeController', 'index');
$router->get('/menu', 'MenuController', 'index');
$router->get('/menu/{restaurantId}', 'MenuController', 'byRestaurant');
$router->get('/product/{id}', 'MenuController', 'show');

// ── Auth Routes ────────────────────────────────────────────
$router->get('/login', 'AuthController', 'loginForm');
$router->post('/login', 'AuthController', 'login', ['csrf']);
$router->get('/register', 'AuthController', 'registerForm');
$router->post('/register', 'AuthController', 'register', ['csrf']);
$router->get('/logout', 'AuthController', 'logout');

// ── Cart / Order (API) ────────────────────────────────────
$router->get('/cart', 'CartController', 'index');
$router->post('/api/cart/add', 'ApiCartController', 'add', ['csrf']);
$router->post('/api/cart/update', 'ApiCartController', 'update', ['csrf']);
$router->post('/api/cart/remove', 'ApiCartController', 'remove', ['csrf']);
$router->get('/api/cart/get', 'ApiCartController', 'get');
$router->post('/api/order/place', 'ApiOrderController', 'place', ['auth', 'csrf']);

// ── Client Account ─────────────────────────────────────────
$router->get('/account', 'AccountController', 'index', ['auth']);
$router->get('/account/orders', 'AccountController', 'orders', ['auth']);
$router->get('/account/order/{id}', 'AccountController', 'orderDetail', ['auth']);

// ── Kitchen Dashboard ──────────────────────────────────────
$router->get('/kitchen', 'KitchenController', 'index', ['kitchen']);
$router->get('/api/kitchen/orders', 'ApiKitchenController', 'orders', ['kitchen']);
$router->post('/api/kitchen/status', 'ApiKitchenController', 'updateStatus', ['kitchen', 'csrf']);

// ── Admin Dashboard ────────────────────────────────────────
$router->get('/admin', 'AdminController', 'index', ['admin']);
$router->get('/admin/products', 'AdminController', 'products', ['admin']);
$router->get('/admin/product/create', 'AdminController', 'createProduct', ['admin']);
$router->post('/admin/product/store', 'AdminController', 'storeProduct', ['admin', 'csrf']);
$router->get('/admin/product/edit/{id}', 'AdminController', 'editProduct', ['admin']);
$router->post('/admin/product/update/{id}', 'AdminController', 'updateProduct', ['admin', 'csrf']);
$router->post('/admin/product/delete/{id}', 'AdminController', 'deleteProduct', ['admin', 'csrf']);
$router->post('/admin/product/toggle/{id}', 'AdminController', 'toggleProduct', ['admin', 'csrf']);

$router->get('/admin/categories', 'AdminController', 'categories', ['admin']);
$router->post('/admin/category/store', 'AdminController', 'storeCategory', ['admin', 'csrf']);
$router->post('/admin/category/update/{id}', 'AdminController', 'updateCategory', ['admin', 'csrf']);
$router->post('/admin/category/delete/{id}', 'AdminController', 'deleteCategory', ['admin', 'csrf']);

$router->get('/admin/restaurants', 'AdminController', 'restaurants', ['admin']);
$router->post('/admin/restaurant/update/{id}', 'AdminController', 'updateRestaurant', ['admin', 'csrf']);

$router->get('/admin/orders', 'AdminController', 'orders', ['admin']);
$router->get('/admin/order/{id}', 'AdminController', 'orderDetail', ['admin']);

$router->post('/admin/product/{id}/option/store', 'AdminController', 'storeOption', ['admin', 'csrf']);
$router->post('/admin/product/option/update/{optionId}', 'AdminController', 'updateOption', ['admin', 'csrf']);
$router->post('/admin/product/option/delete/{optionId}', 'AdminController', 'deleteOption', ['admin', 'csrf']);

$router->get('/admin/supplements', 'AdminController', 'supplements', ['admin']);
$router->post('/admin/supplements/store', 'AdminController', 'storeSupplement', ['admin', 'csrf']);
$router->post('/admin/supplements/update/{id}', 'AdminController', 'updateSupplement', ['admin', 'csrf']);
$router->post('/admin/supplements/delete/{id}', 'AdminController', 'deleteSupplement', ['admin', 'csrf']);

// ── API: Menu data ─────────────────────────────────────────
$router->get('/api/menu/{restaurantId}', 'ApiMenuController', 'getMenu');
$router->get('/api/product/{id}', 'ApiMenuController', 'getProduct');
