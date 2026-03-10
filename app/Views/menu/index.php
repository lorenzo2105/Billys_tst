<!-- Restaurant Selector -->
<section class="menu-header">
    <div class="container">
        <div class="menu-header__top">
            <h1 class="menu-header__title">Notre Menu</h1>
            <div class="restaurant-selector">
                <label for="restaurantSelect">📍 Restaurant :</label>
                <select id="restaurantSelect" onchange="window.location.href='<?= $baseUrl ?>/menu/' + this.value">
                    <?php foreach ($restaurants as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= (int)$r['id'] === (int)$restaurant['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Category Tabs -->
        <div class="category-tabs" id="categoryTabs">
            <button class="category-tab active" data-category="all">Tout</button>
            <?php foreach ($categories as $cat): ?>
                <button class="category-tab" data-category="<?= htmlspecialchars($cat['slug']) ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Menu Content -->
<section class="section">
    <div class="container">
        <input type="hidden" id="currentRestaurantId" value="<?= $restaurant['id'] ?>">

        <?php if (empty($menuByCategory)): ?>
            <div class="empty-state">
                <span class="empty-state__icon">🍽️</span>
                <h3>Aucun produit disponible</h3>
                <p>Le menu de ce restaurant n'est pas encore configuré.</p>
            </div>
        <?php else: ?>
            <?php foreach ($menuByCategory as $slug => $catData): ?>
            <div class="menu-category" data-category-slug="<?= htmlspecialchars($slug) ?>">
                <h2 class="menu-category__title"><?= htmlspecialchars($catData['name']) ?></h2>
                <div class="products-grid">
                    <?php foreach ($catData['products'] as $product): ?>
                    <div class="product-card <?= $product['status'] === 'out_of_stock' ? 'product-card--unavailable' : '' ?>"
                         data-product-id="<?= $product['id'] ?>">
                        <div class="product-card__image">
                            <?php if ($product['image']): ?>
                                <img src="<?= $baseUrl ?>/<?= htmlspecialchars($product['image']) ?>"
                                     alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
                            <?php else: ?>
                                <div class="product-card__placeholder">
                                    <?php
                                    $icons = ['burgers'=>'🍔','poulet'=>'🍗','accompagnements'=>'🍟','boissons'=>'🥤','desserts'=>'🍪','menus'=>'🍱'];
                                    echo $icons[$slug] ?? '🍽️';
                                    ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($product['status'] === 'out_of_stock'): ?>
                                <span class="product-card__badge product-card__badge--out">Rupture</span>
                            <?php elseif (isset($product['stock_status']) && $product['stock_status'] === 'low_stock'): ?>
                                <span class="product-card__badge product-card__badge--low">Stock limité</span>
                            <?php elseif ($product['is_featured']): ?>
                                <span class="product-card__badge">Populaire</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-card__body">
                            <h3 class="product-card__name"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="product-card__desc"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                            <div class="product-card__footer">
                                <span class="product-card__price"><?= formatPrice((float)$product['price']) ?></span>
                                <?php if ($product['status'] === 'available' && ($product['is_available'] ?? true)): ?>
                                    <button class="btn btn--primary btn--sm btn-add-to-cart"
                                            data-product-id="<?= $product['id'] ?>"
                                            data-restaurant-id="<?= $restaurant['id'] ?>"
                                            data-has-options="<?= (int)($product['options_count'] ?? 0) > 0 ? '1' : '0' ?>">
                                        <?= (int)($product['options_count'] ?? 0) > 0 ? '🍱 Composer' : '+ Ajouter' ?>
                                    </button>
                                <?php else: ?>
                                    <span class="btn btn--disabled btn--sm">Indisponible</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Product Configuration Modal -->
<div class="modal" id="productModal">
    <div class="modal__backdrop" onclick="App.closeProductModal()"></div>
    <div class="modal__content modal__content--config">
        <button class="modal__close" onclick="App.closeProductModal()">&times;</button>
        <div id="productModalBody">
            <div class="modal-loading">⏳ Chargement...</div>
        </div>
    </div>
</div>
