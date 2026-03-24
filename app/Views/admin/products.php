<div class="admin-card">
    <div class="admin-card__header">
        <h2>Produits (<?= count($products) ?>)</h2>
        <a href="<?= $baseUrl ?>/admin/product/create" class="btn btn--primary btn--sm">+ Nouveau produit</a>
    </div>

    <?php if (empty($products)): ?>
        <p class="text-muted p-2">Aucun produit. Créez votre premier produit !</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Catégorie</th>
                        <th>Prix</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if ($product['image']): ?>
                                <img src="<?= $baseUrl ?>/<?= htmlspecialchars($product['image']) ?>" class="table-thumb" alt="">
                            <?php else: ?>
                                <span class="table-thumb-placeholder">🍔</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= htmlspecialchars($product['name']) ?></strong>
                            <?php if ($product['is_featured']): ?><span class="badge badge--gold">★</span><?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['category_name']) ?></td>
                        <td><strong><?= formatPrice((float)$product['price']) ?></strong></td>
                        <td class="table-actions">
                            <a href="<?= $baseUrl ?>/admin/product/edit/<?= $product['id'] ?>" class="btn btn--sm btn--outline" title="Modifier">✏️</a>
                            <form method="POST" action="<?= $baseUrl ?>/admin/product/delete/<?= $product['id'] ?>"
                                  style="display:inline" onsubmit="return confirm('Supprimer ce produit ?')">
                                <?= $csrf ?>
                                <button type="submit" class="btn btn--sm btn--danger" title="Supprimer">🗑️</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
