<div class="admin-card">
    <div class="admin-card__header">
        <h2>Commandes (<?= count($orders) ?>)</h2>
        <form method="GET" action="<?= $baseUrl ?>/admin/orders" style="display:flex;align-items:center;gap:.5rem">
            <select name="restaurant_id" onchange="this.form.submit()"
                    style="padding:.4rem .75rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--bg-gray);color:var(--text);font-size:.9rem">
                <option value="">🏪 Tous les restaurants</option>
                <?php foreach ($restaurants as $r): ?>
                    <option value="<?= $r['id'] ?>" <?= (int)($selectedRestaurantId ?? 0) === (int)$r['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($r['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if ($selectedRestaurantId): ?>
                <a href="<?= $baseUrl ?>/admin/orders" class="btn btn--sm btn--outline" title="Effacer le filtre">✕</a>
            <?php endif; ?>
        </form>
    </div>

    <?php if (empty($orders)): ?>
        <p class="text-muted p-2">Aucune commande<?= $selectedRestaurantId ? ' pour ce restaurant' : '' ?>.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Client</th>
                        <th>Restaurant</th>
                        <th>Articles</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><strong>#<?= htmlspecialchars($order['order_number']) ?></strong></td>
                        <td><?= htmlspecialchars($order['customer_name']) ?></td>
                        <td><?= htmlspecialchars($order['restaurant_name']) ?></td>
                        <td><?= $order['item_count'] ?></td>
                        <td><strong><?= formatPrice((float)$order['total']) ?></strong></td>
                        <td><span class="order-status order-status--<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                        <td>
                            <a href="<?= $baseUrl ?>/admin/order/<?= $order['id'] ?>" class="btn btn--sm btn--outline">👁️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
