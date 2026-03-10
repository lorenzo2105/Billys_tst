<?php $isEdit = !empty($product); ?>
<?php $old = \App\Core\Session::getFlash('old') ?? []; ?>
<?php $errors = \App\Core\Session::getFlash('errors') ?? []; ?>

<div class="admin-card">
    <div class="admin-card__header">
        <h2><?= $isEdit ? 'Modifier le produit' : 'Nouveau produit' ?></h2>
        <a href="<?= $baseUrl ?>/admin/products" class="btn btn--sm btn--outline">← Retour</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert--error">
            <?php foreach ($errors as $field => $msgs): ?>
                <?php foreach ($msgs as $msg): ?>
                    <p><?= htmlspecialchars($msg) ?></p>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data"
          action="<?= $baseUrl ?>/admin/product/<?= $isEdit ? 'update/' . $product['id'] : 'store' ?>"
          class="admin-form">
        <?= $csrf ?>

        <div class="form-grid">
            <div class="form-group">
                <label for="name">Nom du produit *</label>
                <input type="text" id="name" name="name" required
                       value="<?= htmlspecialchars($old['name'] ?? $product['name'] ?? '') ?>">
            </div>

            <div class="form-group">
                <label for="category_id">Catégorie *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"
                            <?= (int)($old['category_id'] ?? $product['category_id'] ?? 0) === (int)$cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="price">🥩 Prix Burger Simple</label>
                <input type="number" id="price" name="price" step="10" min="0" required
                       value="<?= htmlspecialchars($old['price'] ?? $product['price'] ?? '0') ?>">
                <small style="color: var(--text-muted);">Prix du burger avec 1 viande</small>
            </div>

            <div class="form-group">
                <label for="price_double">🥩🥩 Prix Burger Double</label>
                <input type="number" id="price_double" name="price_double" step="10" min="0"
                       value="<?= htmlspecialchars($old['price_double'] ?? $product['price_double'] ?? '') ?>"
                       placeholder="Laisser vide si non burger">
                <small style="color: var(--text-muted);">Prix du burger avec 2 viandes</small>
            </div>

            <div class="form-group">
                <label for="status">Statut</label>
                <select id="status" name="status">
                    <?php $currentStatus = $old['status'] ?? $product['status'] ?? 'available'; ?>
                    <option value="available" <?= $currentStatus === 'available' ? 'selected' : '' ?>>Disponible</option>
                    <option value="unavailable" <?= $currentStatus === 'unavailable' ? 'selected' : '' ?>>Indisponible</option>
                    <option value="out_of_stock" <?= $currentStatus === 'out_of_stock' ? 'selected' : '' ?>>Rupture de stock</option>
                </select>
            </div>

            <div class="form-group form-group--full">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"><?= htmlspecialchars($old['description'] ?? $product['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" id="image" name="image" accept="image/*">
                <?php if ($isEdit && $product['image']): ?>
                    <img src="<?= $baseUrl ?>/<?= htmlspecialchars($product['image']) ?>" class="form-preview" alt="">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="sort_order">Ordre d'affichage</label>
                <input type="number" id="sort_order" name="sort_order" min="0"
                       value="<?= htmlspecialchars($old['sort_order'] ?? $product['sort_order'] ?? '0') ?>">
            </div>

            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" name="is_featured" value="1"
                        <?= ($old['is_featured'] ?? $product['is_featured'] ?? 0) ? 'checked' : '' ?>>
                    Produit mis en avant (Best-Seller)
                </label>
            </div>

            <!-- Supplements Section -->
            <div class="form-group form-group--full" style="border-top:1px solid var(--border);padding-top:1.5rem;margin-top:1.5rem">
                <label style="display:block;margin-bottom:1rem;font-size:1rem;font-weight:700">
                    🍟 Suppléments disponibles pour ce produit
                </label>
                <p style="color:var(--text-muted);font-size:.85rem;margin-bottom:1rem">
                    Cochez les suppléments que vous souhaitez rendre disponibles pour ce produit.
                    <a href="<?= $baseUrl ?>/admin/supplements" target="_blank" style="color:var(--primary)">Gérer tous les suppléments →</a>
                </p>
                <?php if (empty($allSupplements ?? [])): ?>
                    <p style="color:var(--text-muted);font-size:.9rem">
                        Aucun supplément disponible. 
                        <a href="<?= $baseUrl ?>/admin/supplements" style="color:var(--primary)">Créer des suppléments</a>
                    </p>
                <?php else: ?>
                    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:.75rem">
                        <?php foreach ($allSupplements as $supp): ?>
                            <label class="checkbox-label" style="padding:.75rem;background:var(--bg-gray);border-radius:var(--radius-sm);border:1px solid var(--border)">
                                <input type="checkbox" name="supplements[]" value="<?= $supp['id'] ?>"
                                    <?= in_array($supp['id'], $assignedSupplementIds ?? []) ? 'checked' : '' ?>>
                                <span style="flex:1">
                                    <?= htmlspecialchars($supp['name']) ?>
                                    <span style="color:var(--text-muted);font-size:.85rem">
                                        (+<?= formatPrice($supp['price']) ?>)
                                    </span>
                                </span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn--primary btn--lg">
                <?= $isEdit ? 'Enregistrer les modifications' : 'Créer le produit' ?>
            </button>
            <a href="<?= $baseUrl ?>/admin/products" class="btn btn--outline btn--lg">Annuler</a>
        </div>
    </form>
</div>

<?php if ($isEdit): ?>
<?php
$groupLabels = [
    'viande'      => '🥩 Viande',
    'taille_menu' => '🍱 Taille du Menu',
    'supplements' => '➕ Suppléments',
    'sauces'      => '🍶 Sauces',
    'taille'      => '📏 Taille',
];
$optionsByGroup = [];
foreach ($product['options'] ?? [] as $opt) {
    $optionsByGroup[$opt['option_group']][] = $opt;
}
?>
<div class="admin-card" style="margin-top:1.5rem">
    <div class="admin-card__header">
        <h2>Options de ce produit</h2>
        <span class="text-muted" style="font-size:.85rem"><?= count($product['options'] ?? []) ?> option(s)</span>
    </div>

    <?php if (!empty($optionsByGroup)): ?>
        <?php foreach ($optionsByGroup as $group => $opts): ?>
        <?php if ($group === 'viande') continue; // Skip viande - auto-generated from prices ?>
        <div style="margin-bottom:1.25rem">
            <h4 style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:.5rem">
                <?= htmlspecialchars($groupLabels[$group] ?? ucfirst(str_replace('_', ' ', $group))) ?>
                <span class="badge" style="margin-left:.375rem;text-transform:none;letter-spacing:0">
                    <?= ($opts[0]['option_type'] ?? 'checkbox') === 'radio' ? '● Choix unique' : '☑ Multi-sélection' ?>
                </span>
            </h4>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Nom</th><th>Prix modifier</th><th>Actions</th></tr></thead>
                    <tbody>
                        <?php foreach ($opts as $opt): ?>
                        <tr>
                            <td>
                                <form method="POST" action="<?= $baseUrl ?>/admin/product/option/update/<?= $opt['id'] ?>" style="display:inline">
                                    <?= $csrf ?>
                                    <input type="text" name="name" value="<?= htmlspecialchars($opt['name']) ?>" 
                                           style="width:200px;padding:.375rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--bg-gray);color:var(--text);">
                            </td>
                            <td>
                                <input type="number" name="price_modifier" value="<?= (float)$opt['price_modifier'] ?>" 
                                       step="10" 
                                       style="width:120px;padding:.375rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--bg-gray);color:var(--text);">
                            </td>
                            <td style="white-space:nowrap">
                                    <button type="submit" class="btn btn--sm btn--primary">💾 Sauver</button>
                                </form>
                                <form method="POST" action="<?= $baseUrl ?>/admin/product/option/delete/<?= $opt['id'] ?>"
                                      style="display:inline;margin-left:.25rem" onsubmit="return confirm('Supprimer cette option ?')">
                                    <?= $csrf ?>
                                    <button type="submit" class="btn btn--sm btn--danger">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted p-2">Aucune option configurée pour ce produit.</p>
    <?php endif; ?>

    <div style="border-top:1px solid var(--border);padding-top:1rem;margin-top:.5rem">
        <p style="color:var(--text-muted);font-size:.9rem;text-align:center;padding:1rem">
            💡 Les options Simple/Double sont générées automatiquement depuis les prix du burger.<br>
            Les suppléments sont gérés dans <a href="<?= $baseUrl ?>/admin/supplements" style="color:var(--primary)">Admin → Suppléments</a>
        </p>
    </div>
</div>
<?php endif; ?>
