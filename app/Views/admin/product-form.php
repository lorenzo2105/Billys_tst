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
                <input type="number" id="price_double" name="price_double" step="1" min="0"
                       value="<?= htmlspecialchars($old['price_double'] ?? $product['price_double'] ?? '') ?>"
                       placeholder="Laisser vide si non burger"
                       oninput="toggleBurgerMenuSection(this.value)">
                <small style="color: var(--text-muted);">Prix du burger avec 2 viandes (laisser vide si ce n'est pas un burger)</small>
            </div>

            <!-- Burger Menu Configuration -->
            <?php $hasPriceDouble = !empty($old['price_double'] ?? $product['price_double'] ?? ''); ?>
            <div class="form-group form-group--full" id="burgerMenuSection"
                 style="<?= $hasPriceDouble ? '' : 'display:none;' ?>border:2px solid var(--primary);border-radius:var(--radius);padding:1.25rem;background:var(--bg-gray);">
                <label style="display:block;margin-bottom:.5rem;font-size:.95rem;font-weight:700">
                    🍱 Configuration des Menus Burger
                </label>
                <p style="color:var(--text-muted);font-size:.85rem;margin-bottom:1rem">
                    Définissez le supplément de prix pour les formules. <strong>Burger seul</strong> est toujours inclus sans frais. Laisser vide pour désactiver une formule.
                </p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                    <div class="form-group" style="margin-bottom:0">
                        <label for="menu_m_price">🍱 Supplément Menu M <small style="color:var(--text-muted)">(Frites M + Boisson M)</small></label>
                        <input type="number" id="menu_m_price" name="menu_m_price" step="1" min="0"
                               value="<?= htmlspecialchars(isset($menuPrices['Menu M']) ? (string)$menuPrices['Menu M'] : '') ?>"
                               placeholder="ex: 350">
                    </div>
                    <div class="form-group" style="margin-bottom:0">
                        <label for="menu_l_price">🍱 Supplément Menu L <small style="color:var(--text-muted)">(Frites L + Boisson L)</small></label>
                        <input type="number" id="menu_l_price" name="menu_l_price" step="1" min="0"
                               value="<?= htmlspecialchars(isset($menuPrices['Menu L']) ? (string)$menuPrices['Menu L'] : '') ?>"
                               placeholder="ex: 450">
                    </div>
                </div>
            </div>
            <script>
            function toggleBurgerMenuSection(val) {
                document.getElementById('burgerMenuSection').style.display = val ? '' : 'none';
            }
            </script>

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

            <!-- Restaurant Availability Section (edit only) -->
            <?php if ($isEdit && !empty($restaurantAvailability ?? [])): ?>
            <div class="form-group form-group--full" style="border-top:1px solid var(--border);padding-top:1.5rem;margin-top:1.5rem">
                <label style="display:block;margin-bottom:.5rem;font-size:1rem;font-weight:700">
                    🏪 Disponibilité par restaurant
                </label>
                <p style="color:var(--text-muted);font-size:.85rem;margin-bottom:1rem">
                    Décochez un restaurant pour rendre ce produit indisponible uniquement dans ce point de vente.
                </p>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:.75rem">
                    <?php foreach ($restaurantAvailability as $rid => $ra): ?>
                        <label class="checkbox-label" style="padding:.875rem;background:var(--bg-gray);border-radius:var(--radius-sm);border:2px solid <?= $ra['is_available'] ? 'var(--success,#22c55e)' : 'var(--danger,#ef4444)' ?>;cursor:pointer"
                               id="resto-label-<?= $rid ?>">
                            <input type="checkbox" name="available_restaurants[]" value="<?= $rid ?>"
                                   <?= $ra['is_available'] ? 'checked' : '' ?>
                                   onchange="document.getElementById('resto-label-<?= $rid ?>').style.borderColor=this.checked?'var(--success,#22c55e)':'var(--danger,#ef4444)'">
                            <span style="flex:1">
                                <strong><?= htmlspecialchars($ra['restaurant_name']) ?></strong>
                                <span style="display:block;font-size:.8rem;color:var(--text-muted);margin-top:.15rem">
                                    <?= $ra['is_available'] ? '✅ Disponible' : '❌ Indisponible' ?>
                                </span>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

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
