<section class="section">
    <div class="container">
        <a href="<?= $baseUrl ?>/menu" class="btn btn--outline btn--sm mb-2">← Retour au menu</a>

        <div class="product-detail">
            <div class="product-detail__image">
                <?php if ($product['image']): ?>
                    <img src="<?= $baseUrl ?>/<?= htmlspecialchars($product['image']) ?>"
                         alt="<?= htmlspecialchars($product['name']) ?>">
                <?php else: ?>
                    <div class="product-card__placeholder product-card__placeholder--lg">🍔</div>
                <?php endif; ?>
            </div>

            <div class="product-detail__info">
                <?php if ($product['category']): ?>
                    <span class="product-card__category"><?= htmlspecialchars($product['category']['name']) ?></span>
                <?php endif; ?>

                <h1 class="product-detail__name"><?= htmlspecialchars($product['name']) ?></h1>
                <p class="product-detail__desc"><?= htmlspecialchars($product['description'] ?? '') ?></p>

                <div class="product-detail__price" id="productTotalPrice">
                    <?= formatPrice((float)$product['price']) ?>
                </div>

                <?php if ($product['status'] !== 'available'): ?>
                    <div class="alert alert--error">Ce produit est actuellement indisponible.</div>
                <?php else: ?>

                    <!-- Options -->
                    <?php if (!empty($product['options'])): ?>
                        <div class="product-options" id="productOptions">
                            <?php
                            $grouped = [];
                            foreach ($product['options'] as $opt) {
                                $grouped[$opt['option_group']][] = $opt;
                            }
                            ?>
                            <?php foreach ($grouped as $group => $options): ?>
                                <div class="option-group">
                                    <h4 class="option-group__title"><?= htmlspecialchars(ucfirst($group)) ?></h4>
                                    <?php foreach ($options as $opt): ?>
                                        <label class="option-item">
                                            <input type="checkbox" name="options[]" value="<?= $opt['id'] ?>"
                                                   data-price="<?= $opt['price_modifier'] ?>"
                                                   class="product-option-cb">
                                            <span class="option-item__name"><?= htmlspecialchars($opt['name']) ?></span>
                                            <?php if ((float)$opt['price_modifier'] != 0): ?>
                                                <span class="option-item__price">
                                                    <?= (float)$opt['price_modifier'] > 0 ? '+' : '' ?><?= formatPrice(abs((float)$opt['price_modifier'])) ?>
                                                </span>
                                            <?php endif; ?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Quantity + Add to Cart -->
                    <div class="product-detail__actions">
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn" id="qtyMinus">−</button>
                            <input type="number" id="productQty" value="1" min="1" max="20" readonly>
                            <button type="button" class="qty-btn" id="qtyPlus">+</button>
                        </div>
                        <button class="btn btn--primary btn--lg" id="addToCartBtn"
                                data-product-id="<?= $product['id'] ?>"
                                data-base-price="<?= $product['price'] ?>">
                            Ajouter au panier 🛒
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
