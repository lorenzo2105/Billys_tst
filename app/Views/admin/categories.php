<div class="admin-card">
    <div class="admin-card__header">
        <h2>Catégories (<?= count($categories) ?>)</h2>
    </div>

    <!-- Add Category Form -->
    <div class="admin-card__section">
        <h3>Ajouter une catégorie</h3>
        <form method="POST" action="<?= $baseUrl ?>/admin/category/store" class="inline-form" enctype="multipart/form-data">
            <?= $csrf ?>
            <input type="text" name="name" placeholder="Nom de la catégorie" required>
            <input type="text" name="description" placeholder="Description (optionnel)">
            <input type="number" name="sort_order" placeholder="Ordre" value="0" min="0" style="width:80px">
            <input type="file" name="image" accept="image/*">
            <button type="submit" class="btn btn--primary btn--sm">+ Ajouter</button>
        </form>
    </div>

    <!-- Categories List -->
    <?php if (empty($categories)): ?>
        <p class="text-muted p-2">Aucune catégorie.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ordre</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Produits</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <form method="POST" action="<?= $baseUrl ?>/admin/category/update/<?= $cat['id'] ?>">
                            <?= $csrf ?>
                            <td><input type="number" name="sort_order" value="<?= $cat['sort_order'] ?>" style="width:60px" min="0"></td>
                            <td><input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required></td>
                            <td><input type="text" name="description" value="<?= htmlspecialchars($cat['description'] ?? '') ?>"></td>
                            <td><span class="badge"><?= $cat['product_count'] ?? 0 ?></span></td>
                            <td>
                                <label class="toggle">
                                    <input type="checkbox" name="is_active" value="1" <?= $cat['is_active'] ? 'checked' : '' ?>>
                                    <span class="toggle__slider"></span>
                                </label>
                            </td>
                            <td class="table-actions">
                                <button type="submit" class="btn btn--sm btn--primary">💾</button>
                        </form>
                                <form method="POST" action="<?= $baseUrl ?>/admin/category/delete/<?= $cat['id'] ?>"
                                      style="display:inline" onsubmit="return confirm('Supprimer cette catégorie et tous ses produits ?')">
                                    <?= $csrf ?>
                                    <button type="submit" class="btn btn--sm btn--danger">🗑️</button>
                                </form>
                            </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
