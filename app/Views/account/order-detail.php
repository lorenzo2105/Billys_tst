<section class="section">
    <div class="container">
        <a href="<?= $baseUrl ?>/account/orders" class="btn btn--outline btn--sm mb-2">← Retour aux commandes</a>

        <div class="order-detail">
            <div class="order-detail__header">
                <div>
                    <h1>Commande #<?= htmlspecialchars($order['order_number']) ?></h1>
                    <p>Passée le <?= date('d/m/Y à H:i', strtotime($order['created_at'])) ?></p>
                </div>
                <span class="order-status order-status--<?= $order['status'] ?> order-status--lg">
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

            <div class="order-detail__restaurant">
                <h3>📍 <?= htmlspecialchars($order['restaurant']['name'] ?? '') ?></h3>
                <p><?= htmlspecialchars($order['restaurant']['address'] ?? '') ?></p>
            </div>

            <div class="order-detail__items">
                <h3>Articles commandés</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Qté</th>
                            <th>Prix unit.</th>
                            <th>Options</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= formatPrice((float)$item['unit_price']) ?></td>
                            <td>
                                <?php
                                $opts = json_decode($item['options_json'] ?? '[]', true);
                                echo !empty($opts) ? htmlspecialchars(implode(', ', $opts)) : '-';
                                ?>
                            </td>
                            <td><strong><?= formatPrice((float)$item['line_total']) ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="order-detail__totals">
                <div class="cart-summary__line"><span>Sous-total</span><span><?= formatPrice((float)$order['subtotal']) ?></span></div>
                <div class="cart-summary__line"><span>TVA (10%)</span><span><?= formatPrice((float)$order['tax']) ?></span></div>
                <div class="cart-summary__line cart-summary__line--total"><span>Total</span><span><?= formatPrice((float)$order['total']) ?></span></div>
            </div>

            <?php if (!empty($order['status_history'])): ?>
            <div class="order-detail__history">
                <h3>Historique</h3>
                <div class="timeline">
                    <?php foreach ($order['status_history'] as $h): ?>
                    <div class="timeline__item">
                        <span class="timeline__dot"></span>
                        <div class="timeline__content">
                            <strong><?= htmlspecialchars(ucfirst($h['status'])) ?></strong>
                            <span><?= date('d/m/Y H:i', strtotime($h['created_at'])) ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
