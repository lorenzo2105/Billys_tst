<div class="admin-card">
    <div class="admin-card__header">
        <h2>Commande #<?= htmlspecialchars($order['order_number']) ?></h2>
        <a href="<?= $baseUrl ?>/admin/orders" class="btn btn--sm btn--outline">← Retour</a>
    </div>

    <div class="order-detail-grid">
        <div class="order-detail__section">
            <h3>Informations</h3>
            <div class="info-list">
                <div class="info-item"><span class="info-label">Client</span><span class="info-value"><?= htmlspecialchars($order['customer_name']) ?></span></div>
                <div class="info-item"><span class="info-label">Téléphone</span><span class="info-value"><?= htmlspecialchars($order['customer_phone'] ?? '-') ?></span></div>
                <div class="info-item"><span class="info-label">Email</span><span class="info-value"><?= htmlspecialchars($order['customer_email'] ?? '-') ?></span></div>
                <div class="info-item"><span class="info-label">Restaurant</span><span class="info-value"><?= htmlspecialchars($order['restaurant']['name'] ?? '') ?></span></div>
                <div class="info-item"><span class="info-label">Statut</span><span class="order-status order-status--<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></div>
                <div class="info-item"><span class="info-label">Date</span><span class="info-value"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span></div>
                <?php if ($order['notes']): ?>
                    <div class="info-item"><span class="info-label">Notes</span><span class="info-value"><?= htmlspecialchars($order['notes']) ?></span></div>
                <?php endif; ?>
            </div>
        </div>

        <div class="order-detail__section">
            <h3>Articles</h3>
            <table class="table">
                <thead>
                    <tr><th>Produit</th><th>Qté</th><th>Prix</th><th>Total</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($item['product_name']) ?>
                            <?php $opts = json_decode($item['options_json'] ?? '[]', true); ?>
                            <?php if (!empty($opts)): ?>
                                <br><small class="text-muted"><?= htmlspecialchars(implode(', ', $opts)) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= formatPrice((float)$item['unit_price']) ?></td>
                        <td><strong><?= formatPrice((float)$item['line_total']) ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="order-detail__totals">
                <div class="cart-summary__line"><span>Sous-total</span><span><?= formatPrice((float)$order['subtotal']) ?></span></div>
                <div class="cart-summary__line"><span>TVA (10%)</span><span><?= formatPrice((float)$order['tax']) ?></span></div>
                <div class="cart-summary__line cart-summary__line--total"><span>Total</span><span><?= formatPrice((float)$order['total']) ?></span></div>
            </div>
        </div>
    </div>
</div>
