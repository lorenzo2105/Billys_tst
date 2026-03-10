<div class="admin-dashboard">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-card--orange">
            <div class="stat-card__icon">📦</div>
            <div class="stat-card__info">
                <span class="stat-card__value"><?= $stats['today_count'] ?></span>
                <span class="stat-card__label">Commandes aujourd'hui</span>
            </div>
        </div>
        <div class="stat-card stat-card--green">
            <div class="stat-card__icon">💰</div>
            <div class="stat-card__info">
                <span class="stat-card__value"><?= formatPrice($stats['today_revenue']) ?></span>
                <span class="stat-card__label">CA aujourd'hui</span>
            </div>
        </div>
        <div class="stat-card stat-card--blue">
            <div class="stat-card__icon">⏳</div>
            <div class="stat-card__info">
                <span class="stat-card__value"><?= $stats['pending_count'] ?></span>
                <span class="stat-card__label">En attente</span>
            </div>
        </div>
        <div class="stat-card stat-card--purple">
            <div class="stat-card__icon">🍔</div>
            <div class="stat-card__info">
                <span class="stat-card__value"><?= $productCount ?></span>
                <span class="stat-card__label">Produits</span>
            </div>
        </div>
    </div>

    <!-- Global Stats -->
    <div class="stats-grid stats-grid--2">
        <div class="stat-card">
            <div class="stat-card__info">
                <span class="stat-card__value"><?= $stats['total_count'] ?></span>
                <span class="stat-card__label">Total commandes</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__info">
                <span class="stat-card__value"><?= formatPrice($stats['total_revenue']) ?></span>
                <span class="stat-card__label">CA total</span>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="admin-card">
        <div class="admin-card__header">
            <h2>Dernières commandes</h2>
            <a href="<?= $baseUrl ?>/admin/orders" class="btn btn--sm btn--outline">Voir tout</a>
        </div>
        <?php if (empty($recentOrders)): ?>
            <p class="text-muted p-2">Aucune commande pour le moment.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Client</th>
                            <th>Restaurant</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td><a href="<?= $baseUrl ?>/admin/order/<?= $order['id'] ?>">#<?= htmlspecialchars($order['order_number']) ?></a></td>
                            <td><?= htmlspecialchars($order['customer_name']) ?></td>
                            <td><?= htmlspecialchars($order['restaurant_name']) ?></td>
                            <td><strong><?= formatPrice((float)$order['total']) ?></strong></td>
                            <td><span class="order-status order-status--<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                            <td><?= date('d/m H:i', strtotime($order['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
