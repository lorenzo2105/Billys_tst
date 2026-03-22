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
                        <?php
                        $groupLabels = [
                            'viande'      => '🥩 Votre viande',
                            'taille_menu' => '🍱 Votre formule',
                            'supplements' => '➕ Suppléments',
                            'sauces'      => '🍶 Sauce',
                            'taille'      => '📏 Taille',
                        ];
                        $groupOrder = ['viande', 'taille_menu', 'supplements', 'sauces', 'taille'];
                        $radioGroups = ['viande', 'taille_menu', 'sauces'];
                        $grouped = [];
                        foreach ($product['options'] as $opt) {
                            $grouped[$opt['option_group']][] = $opt;
                        }
                        $sortedGroups = [];
                        foreach ($groupOrder as $g) {
                            if (isset($grouped[$g])) $sortedGroups[$g] = $grouped[$g];
                        }
                        foreach ($grouped as $g => $opts) {
                            if (!isset($sortedGroups[$g])) $sortedGroups[$g] = $opts;
                        }
                        ?>
                        <div class="product-options" id="productOptions">
                            <?php foreach ($sortedGroups as $group => $options): ?>
                                <?php
                                $isRadio = in_array($group, $radioGroups)
                                    || (($options[0]['option_type'] ?? '') === 'radio');
                                ?>
                                <div class="option-group" data-group="<?= $group ?>">
                                    <h4 class="option-group__title">
                                        <?= $groupLabels[$group] ?? htmlspecialchars(ucfirst(str_replace('_', ' ', $group))) ?>
                                    </h4>
                                    <div class="option-pills">
                                        <?php foreach ($options as $i => $opt): ?>
                                            <?php
                                            $priceNum = (float)$opt['price_modifier'];
                                            $isMenuFormule = $group === 'taille_menu'
                                                && $opt['id'] !== 'burger_seul'
                                                && $priceNum > 0;
                                            ?>
                                            <label class="option-pill">
                                                <input
                                                    type="<?= $isRadio ? 'radio' : 'checkbox' ?>"
                                                    name="<?= $isRadio ? 'opt_' . $group : 'options[]' ?>"
                                                    value="<?= $opt['id'] ?>"
                                                    data-price="<?= $opt['price_modifier'] ?>"
                                                    class="product-option-input"
                                                    <?= ($isRadio && $i === 0) ? 'checked' : '' ?>>
                                                <span class="option-pill__body">
                                                    <span class="option-pill__name"><?= htmlspecialchars($opt['name']) ?></span>
                                                    <?php if ($priceNum != 0): ?>
                                                        <span class="option-pill__price">
                                                            <?= $priceNum > 0 ? '+' : '' ?><?= formatPrice(abs($priceNum)) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if ($isMenuFormule): ?>
                                                        <span style="display:block;font-size:.72rem;opacity:.75;margin-top:.1rem">🍟+🥤 inclus</span>
                                                    <?php endif; ?>
                                                </span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
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
