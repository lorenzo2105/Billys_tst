<section class="section">
    <div class="container">
        <h1 class="section__title">Mon Panier 🛒</h1>

        <div class="cart-layout">
            <!-- Cart Items -->
            <div class="cart-items" id="cartItems">
                <div class="loading" id="cartLoading">Chargement du panier...</div>
                <div class="empty-state" id="cartEmpty" style="display:none;">
                    <span class="empty-state__icon">🛒</span>
                    <h3>Votre panier est vide</h3>
                    <p>Ajoutez des articles depuis notre menu.</p>
                    <a href="<?= $baseUrl ?>/menu" class="btn btn--primary">Voir le menu</a>
                </div>
                <div id="cartItemsList"></div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary" id="cartSummary" style="display:none;">
                <h3>Résumé de la commande</h3>

                <div class="cart-summary__restaurant" id="cartRestaurantInfo"></div>

                <div class="cart-summary__lines">
                    <div class="cart-summary__line">
                        <span>Sous-total</span>
                        <span id="cartSubtotal">0,00 €</span>
                    </div>
                    <div class="cart-summary__line">
                        <span>TVA (10%)</span>
                        <span id="cartTax">0,00 €</span>
                    </div>
                    <div class="cart-summary__line cart-summary__line--total">
                        <span>Total</span>
                        <span id="cartTotal">0,00 €</span>
                    </div>
                </div>

                <?php if (\App\Core\Auth::check()): ?>
                    <div class="cart-summary__form" id="orderForm">
                        <input type="text" id="customerName" placeholder="Votre nom"
                               value="<?= htmlspecialchars(\App\Core\Auth::user()['name'] ?? '') ?>" required>
                        <input type="tel" id="customerPhone" placeholder="Téléphone (optionnel)">
                        <textarea id="orderNotes" placeholder="Notes (allergies, demandes spéciales...)" rows="2"></textarea>
                        <button class="btn btn--primary btn--lg btn--full" id="placeOrderBtn">
                            Valider la commande ✓
                        </button>
                    </div>
                <?php else: ?>
                    <div class="cart-summary__login">
                        <p>Connectez-vous pour passer commande</p>
                        <a href="<?= $baseUrl ?>/login" class="btn btn--primary btn--full">Se connecter</a>
                        <a href="<?= $baseUrl ?>/register" class="btn btn--outline btn--full">Créer un compte</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Order Success Modal -->
<div class="modal" id="orderSuccessModal">
    <div class="modal__backdrop"></div>
    <div class="modal__content modal__content--sm">
        <div class="order-success">
            <span class="order-success__icon">✅</span>
            <h2>Commande confirmée !</h2>
            <p>Numéro : <strong id="orderNumber"></strong></p>
            <p>Votre commande est en cours de préparation.</p>
            <a href="<?= $baseUrl ?>/account/orders" class="btn btn--primary">Voir mes commandes</a>
            <a href="<?= $baseUrl ?>/menu" class="btn btn--outline">Continuer à commander</a>
        </div>
    </div>
</div>
