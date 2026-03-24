<section class="section">
    <div class="container">
        <h1 class="section__title">Mes Commandes</h1>

        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <span class="empty-state__icon">📋</span>
                <h3>Aucune commande</h3>
                <p>Vous n'avez pas encore passé de commande.</p>
                <a href="<?= $baseUrl ?>/menu" class="btn btn--primary">Commander maintenant</a>
            </div>
        <?php else: ?>
            <div class="orders-list">
                <?php foreach ($orders as $order): ?>
                <a href="<?= $baseUrl ?>/account/order/<?= $order['id'] ?>" class="order-card">
                    <div class="order-card__header">
                        <span class="order-card__number">#<?= htmlspecialchars($order['order_number']) ?></span>
                        <span class="order-status order-status--<?= $order['status'] ?>">
                            <?php
                            echo match($order['status']) {
                                'new' => '🆕 Nouvelle',
                                'preparing' => '👨‍🍳 En préparation',
                                'ready' => '✅ Prête',
                                'completed' => '🏁 Terminée',
                                'cancelled' => '❌ Annulée',
                                default => $order['status'],
                            };
                            ?>
                        </span>
                    </div>
                    <div class="order-card__body">
                        <span>📍 <?= htmlspecialchars($order['restaurant_name']) ?></span>
                        <span>📦 <?= $order['item_count'] ?> article(s)</span>
                        <span>💰 <?= number_format((float)$order['total'], 2, ',', ' ') ?> XPF</span>
                    </div>
                    <div class="order-card__footer">
                        <span><?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?></span>
                        <span class="text-primary">Voir détails →</span>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
