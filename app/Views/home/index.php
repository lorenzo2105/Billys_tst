<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero__content">
            <h1 class="hero__title">Les Meilleurs <span class="text-primary">Burgers</span> de la Ville</h1>
            <p class="hero__subtitle">Savourez nos créations artisanales, préparées avec des ingrédients frais et de qualité.</p>
            <a href="<?= $baseUrl ?>/menu" class="btn btn--primary btn--lg">
                Commander maintenant 🍔
            </a>
        </div>
    </div>
</section>

<!-- Restaurant Selection -->
<section class="section" id="restaurants">
    <div class="container">
        <h2 class="section__title">Choisissez votre restaurant</h2>
        <p class="section__subtitle">3 points de vente pour vous servir</p>
        <div class="restaurants-grid">
            <?php foreach ($restaurants as $restaurant): ?>
            <a href="<?= $baseUrl ?>/menu/<?= $restaurant['id'] ?>" class="restaurant-card">
                <div class="restaurant-card__icon">🏪</div>
                <h3 class="restaurant-card__name"><?= htmlspecialchars($restaurant['name']) ?></h3>
                <p class="restaurant-card__address"><?= htmlspecialchars($restaurant['address']) ?></p>
                <p class="restaurant-card__hours">
                    <span>🕐</span> <?= htmlspecialchars($restaurant['opening_hours'] ?? 'Horaires non définis') ?>
                </p>
                <?php if ($restaurant['phone']): ?>
                <p class="restaurant-card__phone">
                    <span>📞</span> <?= htmlspecialchars($restaurant['phone']) ?>
                </p>
                <?php endif; ?>
                <span class="btn btn--primary btn--sm">Voir le menu →</span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($featured)): ?>
<section class="section section--gray" id="featured">
    <div class="container">
        <h2 class="section__title">Nos Best-Sellers 🔥</h2>
        <p class="section__subtitle">Les favoris de nos clients</p>
        <div class="products-grid">
            <?php foreach ($featured as $product): ?>
            <div class="product-card product-card--clickable" 
                 data-product-id="<?= $product['id'] ?>"
                 data-has-options="1">
                <div class="product-card__image">
                    <?php if ($product['image']): ?>
                        <img src="<?= $baseUrl ?>/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
                    <?php else: ?>
                        <div class="product-card__placeholder">🍔</div>
                    <?php endif; ?>
                    <?php if ($product['is_featured']): ?>
                        <span class="product-card__badge">Best-Seller</span>
                    <?php endif; ?>
                </div>
                <div class="product-card__body">
                    <span class="product-card__category"><?= htmlspecialchars($product['category_name']) ?></span>
                    <h3 class="product-card__name"><?= htmlspecialchars($product['name']) ?></h3>
                    <p class="product-card__desc"><?= htmlspecialchars($product['description'] ?? '') ?></p>
                    <div class="product-card__footer">
                        <span class="product-card__price"><?= formatPrice((float)$product['price']) ?></span>
                        <span class="btn btn--primary btn--sm">
                            Voir détails →
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="section cta-section">
    <div class="container">
        <div class="cta-box">
            <h2>Prêt à commander ?</h2>
            <p>Choisissez votre restaurant et composez votre commande en quelques clics !</p>
            <a href="<?= $baseUrl ?>/menu" class="btn btn--white btn--lg">Voir le menu complet</a>
        </div>
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
