/**
 * Billy's Fast Food - Main Application JavaScript
 * Vanilla JS ES6+ - Cart, Navigation, AJAX, UI
 */

'use strict';

const App = {
    baseUrl: '',
    csrfToken: '',

    init() {
        this.baseUrl = window.APP?.baseUrl || '';
        this.csrfToken = window.APP?.csrfToken || '';

        this.initNavigation();
        this.initUserMenu();
        this.initCategoryTabs();
        this.initAddToCart();
        this.initProductDetail();
        this.initCartPage();
        this.updateCartBadge();
        this.initFlashAutoHide();
        this.initMenuHeaderScroll();
    },

    // ── Navigation ───────────────────────────────────────────
    initNavigation() {
        const hamburger = document.getElementById('hamburgerBtn');
        const nav = document.getElementById('mainNav');
        if (hamburger && nav) {
            hamburger.addEventListener('click', () => {
                nav.classList.toggle('open');
            });
        }
    },

    initUserMenu() {
        const btn = document.getElementById('userMenuBtn');
        const dropdown = document.getElementById('userDropdown');
        if (btn && dropdown) {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });
            document.addEventListener('click', () => {
                dropdown.classList.remove('show');
            });
        }
    },

    initFlashAutoHide() {
        const flashes = document.querySelectorAll('.alert');
        flashes.forEach(flash => {
            setTimeout(() => {
                flash.style.opacity = '0';
                setTimeout(() => flash.remove(), 300);
            }, 5000);
        });
    },

    // ── Menu Header Scroll Effect ───────────────────────────────
    initMenuHeaderScroll() {
        const menuHeader = document.querySelector('.menu-header');
        if (!menuHeader) return;

        let lastScrollY = window.scrollY;
        let isScrolling = false;
        let scrollTimeout;

        const updateHeaderVisibility = () => {
            const currentScrollY = window.scrollY;
            const scrollDirection = currentScrollY > lastScrollY ? 'down' : 'up';
            
            // Only apply effect if we've scrolled at least 50px
            if (Math.abs(currentScrollY - lastScrollY) > 50) {
                if (scrollDirection === 'down') {
                    menuHeader.style.transform = 'translateY(-100%)';
                    menuHeader.style.opacity = '0';
                } else {
                    menuHeader.style.transform = 'translateY(0)';
                    menuHeader.style.opacity = '1';
                }
                lastScrollY = currentScrollY;
            }
        };

        const handleScroll = () => {
            if (!isScrolling) {
                window.requestAnimationFrame(() => {
                    updateHeaderVisibility();
                    isScrolling = false;
                });
                isScrolling = true;
            }
        };

        // Throttle scroll events
        const throttledScroll = () => {
            if (scrollTimeout) {
                clearTimeout(scrollTimeout);
            }
            scrollTimeout = setTimeout(handleScroll, 16); // ~60fps
        };

        // Add smooth transition to menu header
        menuHeader.style.transition = 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1)';

        window.addEventListener('scroll', throttledScroll, { passive: true });
    },

    // ── Category Tabs ────────────────────────────────────────
    initCategoryTabs() {
        const tabs = document.querySelectorAll('.category-tab');
        const categories = document.querySelectorAll('.menu-category');

        if (!tabs.length || !categories.length) return;

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const slug = tab.dataset.category;

                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                categories.forEach(cat => {
                    if (slug === 'all' || cat.dataset.categorySlug === slug) {
                        cat.style.display = '';
                    } else {
                        cat.style.display = 'none';
                    }
                });
            });
        });
    },

    // ── Add to Cart (Menu page) ──────────────────────────────
    initAddToCart() {
        document.querySelectorAll('.btn-add-to-cart').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = btn.dataset.productId;
                const restaurantId = btn.dataset.restaurantId || document.getElementById('currentRestaurantId')?.value;

                if (!restaurantId) {
                    this.showToast('Veuillez sélectionner un restaurant.', 'error');
                    return;
                }

                if (btn.dataset.hasOptions === '1') {
                    this.openProductModal(productId, restaurantId);
                } else {
                    this.addToCart(productId, 1, restaurantId, []);
                }
            });
        });

        // Quick add from home page (featured products)
        document.querySelectorAll('.add-to-cart-quick').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = btn.dataset.productId;
                // Use first restaurant as default for quick add
                this.addToCart(productId, 1, 1, []);
            });
        });

        // Clickable product cards on home page
        document.querySelectorAll('.product-card--clickable').forEach(card => {
            card.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = card.dataset.productId;
                // Use first restaurant as default for home page
                const restaurantId = 1;
                
                if (card.dataset.hasOptions === '1') {
                    this.openProductModal(productId, restaurantId);
                } else {
                    this.addToCart(productId, 1, restaurantId, []);
                }
            });
        });
    },

    // ── Product Detail Page ──────────────────────────────────
    initProductDetail() {
        const qtyMinus = document.getElementById('qtyMinus');
        const qtyPlus = document.getElementById('qtyPlus');
        const qtyInput = document.getElementById('productQty');
        const addBtn = document.getElementById('addToCartBtn');
        const priceDisplay = document.getElementById('productTotalPrice');

        if (!addBtn) return;

        const basePrice = parseFloat(addBtn.dataset.basePrice) || 0;

        const getInputs = () => document.querySelectorAll('.product-option-input');

        const updatePrice = () => {
            let optionsPrice = 0;
            getInputs().forEach(input => {
                if (input.checked) {
                    optionsPrice += parseFloat(input.dataset.price) || 0;
                }
            });
            const qty = parseInt(qtyInput?.value || '1');
            const total = (basePrice + optionsPrice) * qty;
            if (priceDisplay) {
                priceDisplay.textContent = Math.round(total).toLocaleString('fr-FR') + ' XPF';
            }
        };

        if (qtyMinus && qtyInput) {
            qtyMinus.addEventListener('click', () => {
                const val = parseInt(qtyInput.value);
                if (val > 1) { qtyInput.value = val - 1; updatePrice(); }
            });
        }

        if (qtyPlus && qtyInput) {
            qtyPlus.addEventListener('click', () => {
                const val = parseInt(qtyInput.value);
                if (val < 20) { qtyInput.value = val + 1; updatePrice(); }
            });
        }

        getInputs().forEach(input => input.addEventListener('change', updatePrice));
        updatePrice();

        addBtn.addEventListener('click', () => {
            const productId = addBtn.dataset.productId;
            const qty = parseInt(qtyInput?.value || '1');
            const restaurantId = document.getElementById('currentRestaurantId')?.value || 1;
            const selectedOptions = [];
            getInputs().forEach(input => {
                if (input.checked) selectedOptions.push(input.value);
            });

            this.addToCart(productId, qty, restaurantId, selectedOptions);
        });
    },

    // ── Product Configuration Modal ─────────────────────────
    async openProductModal(productId, restaurantId) {
        const modal = document.getElementById('productModal');
        const body = document.getElementById('productModalBody');
        if (!modal || !body) return;

        body.innerHTML = '<div class="modal-loading">⏳ Chargement...</div>';
        modal.classList.add('open');
        document.body.style.overflow = 'hidden';

        try {
            const res = await this.apiGet(`/api/product/${productId}`);
            if (!res.success) { body.innerHTML = '<p style="padding:2rem;text-align:center">Erreur de chargement.</p>'; return; }

            const product = res.product;
            const grouped = {};
            (product.options || []).forEach(opt => {
                if (!grouped[opt.option_group]) {
                    grouped[opt.option_group] = { type: opt.option_type || 'checkbox', options: [] };
                }
                grouped[opt.option_group].options.push(opt);
            });

            body.innerHTML = this.renderProductModal(product, grouped, restaurantId);
            this.initModalInteractions(parseFloat(product.price));
        } catch (e) {
            body.innerHTML = '<p style="padding:2rem;text-align:center">Erreur de connexion.</p>';
        }
    },

    closeProductModal() {
        document.getElementById('productModal')?.classList.remove('open');
        document.body.style.overflow = '';
    },

    renderProductModal(product, grouped, restaurantId) {
        const groupLabels = {
            'viande':      '🥩 Votre viande',
            'taille_menu': '🍱 Formule',
            'supplements': '➕ Suppléments',
            'sauces':      '🍶 Sauce',
            'taille':      '📏 Taille',
        };

        // Order groups: viande first, then taille_menu, then others
        const groupOrder = ['viande', 'taille_menu', 'supplements', 'sauces', 'taille'];
        const sortedGroups = [];
        
        groupOrder.forEach(g => {
            if (grouped[g]) sortedGroups.push([g, grouped[g]]);
        });
        Object.entries(grouped).forEach(([g, data]) => {
            if (!groupOrder.includes(g)) sortedGroups.push([g, data]);
        });

        let optionsHtml = '';
        sortedGroups.forEach(([group, data]) => {
            const label = groupLabels[group] || group.replace('_', ' ');
            const isRadio = group === 'viande' || group === 'taille_menu' || group === 'sauces' || data.type === 'radio';
            optionsHtml += `
                <div class="option-group">
                    <h4 class="option-group__title">${label}</h4>
                    <div class="option-pills">`;

            data.options.forEach((opt, i) => {
                const inputType = isRadio ? 'radio' : 'checkbox';
                const checked = (isRadio && i === 0) ? 'checked' : '';
                const priceNum = parseFloat(opt.price_modifier);
                const priceLabel = priceNum !== 0
                    ? `<span class="option-pill__price">${priceNum > 0 ? '+' : ''}${Math.round(Math.abs(priceNum)).toLocaleString('fr-FR')} XPF</span>`
                    : '';
                const isMenuFormule = group === 'taille_menu' && opt.id !== 'burger_seul' && priceNum > 0;
                const menuNote = isMenuFormule
                    ? `<span style="display:block;font-size:.72rem;opacity:.75;margin-top:.1rem">🍟+🥤 inclus</span>`
                    : '';
                optionsHtml += `
                    <label class="option-pill">
                        <input type="${inputType}" name="opt_${group}" value="${opt.id}"
                               data-price="${opt.price_modifier}" class="product-option-input" ${checked}>
                        <span class="option-pill__body">
                            <span class="option-pill__name">${this.escapeHtml(opt.name)}</span>
                            ${priceLabel}
                            ${menuNote}
                        </span>
                    </label>`;
            });

            optionsHtml += `</div></div>`;
        });

        return `
            <div class="modal-product">
                <div class="modal-product__header">
                    <h3 class="modal-product__name">${this.escapeHtml(product.name)}</h3>
                    <p class="modal-product__desc">${this.escapeHtml(product.description || '')}</p>
                </div>
                <div class="modal-product__options">${optionsHtml}</div>
                <div class="modal-product__footer">
                    <div class="modal-product__total">
                        <span class="modal-product__total-label">Total</span>
                        <span class="modal-product__total-price" id="modalProductPrice">${Math.round(parseFloat(product.price)).toLocaleString('fr-FR')} XPF</span>
                    </div>
                    <div class="modal-product__actions">
                        <div class="quantity-selector">
                            <button type="button" class="qty-btn" id="modalQtyMinus">−</button>
                            <input type="number" id="modalQty" value="1" min="1" max="20" readonly>
                            <button type="button" class="qty-btn" id="modalQtyPlus">+</button>
                        </div>
                        <button class="btn btn--primary btn--lg"
                                data-product-id="${product.id}"
                                data-restaurant-id="${restaurantId}"
                                data-base-price="${product.price}"
                                onclick="App.addFromModal(this)">
                            Ajouter au panier 🛒
                        </button>
                    </div>
                </div>
            </div>`;
    },

    initModalInteractions(basePrice) {
        const updatePrice = () => {
            let optionsPrice = 0;
            document.querySelectorAll('.product-option-input:checked').forEach(input => {
                optionsPrice += parseFloat(input.dataset.price) || 0;
            });
            const qty = parseInt(document.getElementById('modalQty')?.value || '1');
            const priceEl = document.getElementById('modalProductPrice');
            if (priceEl) {
                priceEl.textContent = Math.round((basePrice + optionsPrice) * qty).toLocaleString('fr-FR') + ' XPF';
            }
        };

        document.querySelectorAll('.product-option-input').forEach(i => i.addEventListener('change', updatePrice));

        document.getElementById('modalQtyMinus')?.addEventListener('click', () => {
            const qty = document.getElementById('modalQty');
            if (qty && parseInt(qty.value) > 1) { qty.value = parseInt(qty.value) - 1; updatePrice(); }
        });
        document.getElementById('modalQtyPlus')?.addEventListener('click', () => {
            const qty = document.getElementById('modalQty');
            if (qty && parseInt(qty.value) < 20) { qty.value = parseInt(qty.value) + 1; updatePrice(); }
        });

        updatePrice();
    },

    addFromModal(btn) {
        const productId = btn.dataset.productId;
        const restaurantId = btn.dataset.restaurantId;
        const qty = parseInt(document.getElementById('modalQty')?.value || '1');
        const selectedOptions = [];
        document.querySelectorAll('.product-option-input:checked').forEach(input => {
            selectedOptions.push(input.value);
        });
        this.closeProductModal();
        this.addToCart(productId, qty, restaurantId, selectedOptions);
    },

    // ── Cart API ─────────────────────────────────────────────
    async addToCart(productId, quantity, restaurantId, options) {
        try {
            const res = await this.apiPost('/api/cart/add', {
                product_id: productId,
                quantity: quantity,
                restaurant_id: restaurantId,
                options: options
            });

            if (res.success) {
                this.showToast(res.message || 'Ajouté au panier !', 'success');
                this.updateCartBadgeValue(res.count);
            } else {
                this.showToast(res.message || 'Erreur', 'error');
            }
        } catch (err) {
            this.showToast('Erreur de connexion.', 'error');
        }
    },

    async updateCartBadge() {
        try {
            const res = await this.apiGet('/api/cart/get');
            if (res.success) {
                this.updateCartBadgeValue(res.count);
            }
        } catch (e) {
            // silently fail
        }
    },

    updateCartBadgeValue(count) {
        const badge = document.getElementById('cartBadge');
        if (badge) {
            badge.textContent = count || 0;
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
    },

    // ── Cart Page ────────────────────────────────────────────
    initCartPage() {
        const cartItemsList = document.getElementById('cartItemsList');
        if (!cartItemsList) return;

        this.loadCart();

        const placeOrderBtn = document.getElementById('placeOrderBtn');
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', () => this.placeOrder());
        }
    },

    async loadCart() {
        const cartItemsList = document.getElementById('cartItemsList');
        const cartLoading = document.getElementById('cartLoading');
        const cartEmpty = document.getElementById('cartEmpty');
        const cartSummary = document.getElementById('cartSummary');

        if (!cartItemsList) return;

        try {
            const res = await this.apiGet('/api/cart/get');

            if (cartLoading) cartLoading.style.display = 'none';

            if (!res.success || !res.cart || res.cart.length === 0) {
                if (cartEmpty) cartEmpty.style.display = '';
                if (cartSummary) cartSummary.style.display = 'none';
                cartItemsList.innerHTML = '';
                return;
            }

            if (cartEmpty) cartEmpty.style.display = 'none';
            if (cartSummary) cartSummary.style.display = '';

            this.renderCartItems(res.cart);
            this.updateCartTotals(res.total);
            this.updateCartBadgeValue(res.count);

            // Restaurant info
            const restInfo = document.getElementById('cartRestaurantInfo');
            if (restInfo && res.restaurant_id) {
                restInfo.textContent = '📍 Restaurant #' + res.restaurant_id;
            }

        } catch (err) {
            if (cartLoading) cartLoading.textContent = 'Erreur de chargement du panier.';
        }
    },

    renderCartItems(cart) {
        const container = document.getElementById('cartItemsList');
        if (!container) return;

        container.innerHTML = cart.map(item => `
            <div class="cart-item" data-key="${this.escapeHtml(item.key)}">
                <div class="cart-item__image">🍔</div>
                <div class="cart-item__info">
                    <div class="cart-item__name">${this.escapeHtml(item.name)}</div>
                    ${item.option_names && item.option_names.length > 0
                        ? `<div class="cart-item__options">${item.option_names.map(o => this.escapeHtml(o)).join(', ')}</div>`
                        : ''}
                    <div class="cart-item__price">${Math.round((item.price + (item.options_price || 0)) * item.quantity).toLocaleString('fr-FR')} XPF</div>
                </div>
                <div class="cart-item__actions">
                    <div class="cart-item__qty">
                        <button onclick="App.updateCartItem('${this.escapeHtml(item.key)}', ${item.quantity - 1})">−</button>
                        <span>${item.quantity}</span>
                        <button onclick="App.updateCartItem('${this.escapeHtml(item.key)}', ${item.quantity + 1})">+</button>
                    </div>
                    <button class="cart-item__remove" onclick="App.removeCartItem('${this.escapeHtml(item.key)}')">🗑️</button>
                </div>
            </div>
        `).join('');
    },

    updateCartTotals(subtotal) {
        const tax = subtotal * 0.10;
        const total = subtotal + tax;

        const elSub = document.getElementById('cartSubtotal');
        const elTax = document.getElementById('cartTax');
        const elTotal = document.getElementById('cartTotal');

        if (elSub) elSub.textContent = Math.round(subtotal).toLocaleString('fr-FR') + ' XPF';
        if (elTax) elTax.textContent = Math.round(tax).toLocaleString('fr-FR') + ' XPF';
        if (elTotal) elTotal.textContent = Math.round(total).toLocaleString('fr-FR') + ' XPF';
    },

    async updateCartItem(cartKey, quantity) {
        try {
            const res = await this.apiPost('/api/cart/update', {
                cart_key: cartKey,
                quantity: quantity
            });
            if (res.success) {
                this.loadCart();
            }
        } catch (e) {
            this.showToast('Erreur', 'error');
        }
    },

    async removeCartItem(cartKey) {
        try {
            const res = await this.apiPost('/api/cart/remove', {
                cart_key: cartKey
            });
            if (res.success) {
                this.loadCart();
            }
        } catch (e) {
            this.showToast('Erreur', 'error');
        }
    },

    // ── Place Order ──────────────────────────────────────────
    async placeOrder() {
        const btn = document.getElementById('placeOrderBtn');
        const name = document.getElementById('customerName')?.value?.trim();
        const phone = document.getElementById('customerPhone')?.value?.trim();
        const notes = document.getElementById('orderNotes')?.value?.trim();

        if (!name) {
            this.showToast('Veuillez entrer votre nom.', 'error');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Envoi en cours...';

        try {
            const res = await this.apiPost('/api/order/place', {
                customer_name: name,
                customer_phone: phone,
                notes: notes
            });

            if (res.success) {
                const orderNumber = document.getElementById('orderNumber');
                if (orderNumber) orderNumber.textContent = res.order_number;

                const modal = document.getElementById('orderSuccessModal');
                if (modal) modal.classList.add('open');

                this.updateCartBadgeValue(0);
            } else {
                this.showToast(res.message || 'Erreur lors de la commande.', 'error');
                btn.disabled = false;
                btn.textContent = 'Valider la commande ✓';
            }
        } catch (e) {
            this.showToast('Erreur de connexion.', 'error');
            btn.disabled = false;
            btn.textContent = 'Valider la commande ✓';
        }
    },

    // ── API Helpers ──────────────────────────────────────────
    async apiGet(endpoint) {
        const res = await fetch(this.baseUrl + endpoint, {
            headers: { 'Accept': 'application/json' }
        });
        return res.json();
    },

    async apiPost(endpoint, data) {
        const res = await fetch(this.baseUrl + endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify({
                ...data,
                _csrf_token: this.csrfToken
            })
        });
        return res.json();
    },

    // ── Toast Notifications ──────────────────────────────────
    showToast(message, type = 'success') {
        const existing = document.querySelector('.toast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.innerHTML = `<span>${this.escapeHtml(message)}</span>`;
        toast.style.cssText = `
            position: fixed;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            background: ${type === 'success' ? '#06d6a0' : '#ef476f'};
            color: #fff;
            padding: .75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: .9rem;
            z-index: 9999;
            box-shadow: 0 4px 20px rgba(0,0,0,.2);
            animation: toastIn .3s ease;
            max-width: 90vw;
            text-align: center;
        `;

        // Add animation keyframes if not already added
        if (!document.getElementById('toastStyles')) {
            const style = document.createElement('style');
            style.id = 'toastStyles';
            style.textContent = `
                @keyframes toastIn { from { opacity:0; transform:translateX(-50%) translateY(20px); } to { opacity:1; transform:translateX(-50%) translateY(0); } }
                @keyframes toastOut { from { opacity:1; } to { opacity:0; transform:translateX(-50%) translateY(20px); } }
            `;
            document.head.appendChild(style);
        }

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.animation = 'toastOut .3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    // ── Utility ──────────────────────────────────────────────
    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = String(text);
        return div.innerHTML;
    }
};

// Boot
document.addEventListener('DOMContentLoaded', () => App.init());
