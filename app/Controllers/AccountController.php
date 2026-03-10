<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Order;
use App\Models\User;

class AccountController extends Controller
{
    public function index(): void
    {
        $user = (new User())->findById(Auth::id());

        $this->layout('account.index', [
            'user'      => $user,
            'pageTitle' => 'Mon Compte',
        ]);
    }

    public function orders(): void
    {
        $orderModel = new Order();
        $orders = $orderModel->getByUser(Auth::id());

        $this->layout('account.orders', [
            'orders'    => $orders,
            'pageTitle' => 'Mes Commandes',
        ]);
    }

    public function orderDetail(string $id): void
    {
        $orderModel = new Order();
        $order = $orderModel->getWithItems((int)$id);

        if (!$order || (int)$order['user_id'] !== Auth::id()) {
            $this->redirect('/account/orders');
            return;
        }

        $this->layout('account.order-detail', [
            'order'     => $order,
            'pageTitle' => 'Commande #' . $order['order_number'],
        ]);
    }
}
