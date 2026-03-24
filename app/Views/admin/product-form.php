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
                <label for="price" id="priceLabel">💰 Prix du produit</label>
                <input type="number" id="price" name="price" step="1" min="0" required
                       value="<?= htmlspecialchars($old['price'] ?? $product['price'] ?? '0') ?>">
                <small style="color: var(--text-muted);" id="priceHelp">Prix de base du produit</small>
            </div>

            <div class="form-group" id="priceDoubleGroup">
                <label for="price_double" id="priceDoubleLabel">🥩🥩 Prix Burger Double</label>
                <input type="number" id="price_double" name="price_double" step="1" min="0"
                       value="<?= htmlspecialchars($old['price_double'] ?? $product['price_double'] ?? '') ?>"
                       placeholder="Laisser vide si non applicable">
                <small style="color: var(--text-muted);" id="priceDoubleHelp">Prix du burger avec 2 viandes (laisser vide si ce n'est pas un burger)</small>
            </div>

            <!-- Burger Menu Configuration -->
            <div class="form-group form-group--full" id="burgerMenuSection"
                 style="border:2px solid var(--primary);border-radius:var(--radius);padding:1.25rem;background:var(--bg-gray);">
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

            <!-- Supplements Info -->
            <div class="form-group form-group--full" style="border-top:1px solid var(--border);padding-top:1.5rem;margin-top:1.5rem">
                <label style="display:block;margin-bottom:1rem;font-size:1rem;font-weight:700">
                    🍟 Suppléments
                </label>
                <p style="color:var(--text-muted);font-size:.9rem;padding:1rem;background:var(--bg-gray);border-radius:var(--radius-sm);border-left:4px solid var(--primary)">
                    💡 <strong>Les suppléments sont gérés automatiquement :</strong><br>
                    • Tous les suppléments actifs s'appliquent automatiquement aux <strong>burgers uniquement</strong><br>
                    • Gérez les suppléments (nom, prix, activation) dans <a href="<?= $baseUrl ?>/admin/supplements" style="color:var(--primary);text-decoration:underline">Admin → Suppléments</a>
                </p>
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

<script>
// Adapter les labels selon la catégorie sélectionnée
function updatePriceLabels() {
    const categorySelect = document.getElementById('category_id');
    const selectedOption = categorySelect.options[categorySelect.selectedIndex];
    const categoryName = selectedOption.text.toLowerCase();
    
    const priceLabel = document.getElementById('priceLabel');
    const priceHelp = document.getElementById('priceHelp');
    const priceDoubleGroup = document.getElementById('priceDoubleGroup');
    const priceDoubleLabel = document.getElementById('priceDoubleLabel');
    const priceDoubleHelp = document.getElementById('priceDoubleHelp');
    const burgerMenuSection = document.getElementById('burgerMenuSection');
    
    // Déterminer le type de produit
    if (categoryName.includes('burger')) {
        // Catégorie Burgers
        priceLabel.innerHTML = '🥩 Prix Burger Simple';
        priceHelp.textContent = 'Prix du burger avec 1 viande';
        priceDoubleLabel.innerHTML = '🥩🥩 Prix Burger Double';
        priceDoubleHelp.textContent = 'Prix du burger avec 2 viandes';
        // priceDoubleGroup.style.display = 'block';
        burgerMenuSection.style.display = 'block';
    } else if (categoryName.includes('poulet')) {
        // Catégorie Poulet
        priceLabel.innerHTML = '🍗 Prix du produit';
        priceHelp.textContent = 'Prix de base du produit poulet';
        priceDoubleLabel.innerHTML = '🍗🍗 Prix portion double';
        priceDoubleHelp.textContent = 'Prix pour une portion double (optionnel)';
        // priceDoubleGroup.style.display = 'block';
        burgerMenuSection.style.display = 'none';
    } else if (categoryName.includes('dessert')) {
        // Catégorie Desserts
        priceLabel.innerHTML = '🍰 Prix du dessert';
        priceHelp.textContent = 'Prix du dessert';
        priceDoubleGroup.style.display = 'none';
        burgerMenuSection.style.display = 'none';
    } else if (categoryName.includes('boisson')) {
        // Catégorie Boissons
        priceLabel.innerHTML = '🥤 Prix de la boisson';
        priceHelp.textContent = 'Prix de la boisson';
        priceDoubleGroup.style.display = 'none';
        burgerMenuSection.style.display = 'none';
    } else if (categoryName.includes('accompagnement')) {
        // Catégorie Accompagnements
        priceLabel.innerHTML = '🍟 Prix de l\'accompagnement';
        priceHelp.textContent = 'Prix de l\'accompagnement';
        priceDoubleGroup.style.display = 'none';
        burgerMenuSection.style.display = 'none';
    } else if (categoryName.includes('menu')) {
        // Catégorie Menus
        priceLabel.innerHTML = '🍱 Prix du menu';
        priceHelp.textContent = 'Prix du menu complet';
        priceDoubleGroup.style.display = 'none';
        burgerMenuSection.style.display = 'none';
    } else {
        // Catégorie par défaut
        priceLabel.innerHTML = '💰 Prix du produit';
        priceHelp.textContent = 'Prix de base du produit';
        priceDoubleLabel.innerHTML = '💰💰 Prix version double';
        priceDoubleHelp.textContent = 'Prix pour une version double (optionnel)';
        priceDoubleGroup.style.display = 'block';
        burgerMenuSection.style.display = 'none';
    }
}

// Appliquer au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    updatePriceLabels();
    
    // Écouter les changements de catégorie
    const categorySelect = document.getElementById('category_id');
    if (categorySelect) {
        categorySelect.addEventListener('change', updatePriceLabels);
    }
});
</script>
