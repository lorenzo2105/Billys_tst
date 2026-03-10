/**
 * Billy's Fast Food - Kitchen Display System (KDS)
 * Real-time order polling with AJAX
 */

'use strict';

const Kitchen = {
    baseUrl: '',
    csrfToken: '',
    pollInterval: null,
    pollDelay: 5000, // 5 seconds
    previousOrderCount: 0,
    currentRestaurantId: null,

    init() {
        this.baseUrl = window.APP?.baseUrl || '';
        this.csrfToken = window.APP?.csrfToken || '';

        this.initClock();
        this.initRestaurantFilter();
        this.startPolling();
    },

    // ── Clock ────────────────────────────────────────────────
    initClock() {
        const clockEl = document.getElementById('kitchenClock');
        if (!clockEl) return;

        const update = () => {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        };
        update();
        setInterval(update, 1000);
    },

    // ── Restaurant Filter ────────────────────────────────────
    initRestaurantFilter() {
        const select = document.getElementById('restaurantFilter');
        if (!select) return;

        select.addEventListener('change', () => {
            this.currentRestaurantId = select.value || null;
            this.fetchOrders();
        });
    },

    // ── Polling ──────────────────────────────────────────────
    startPolling() {
        this.fetchOrders();
        this.pollInterval = setInterval(() => this.fetchOrders(), this.pollDelay);
    },

    stopPolling() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
            this.pollInterval = null;
        }
    },

    // ── Fetch Orders ─────────────────────────────────────────
    async fetchOrders() {
        try {
            let url = this.baseUrl + '/api/kitchen/orders';
            if (this.currentRestaurantId) {
                url += '?restaurant_id=' + this.currentRestaurantId;
            }

            const res = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (data.success) {
                this.renderOrders(data.orders);

                // Play notification sound for new orders
                if (data.count > this.previousOrderCount && this.previousOrderCount > 0) {
                    this.playNotification();
                }
                this.previousOrderCount = data.count;
            }
        } catch (err) {
            console.error('Kitchen polling error:', err);
        }
    },

    // ── Render Orders ────────────────────────────────────────
    renderOrders(orders) {
        const grid = document.getElementById('kitchenGrid');
        const loading = document.getElementById('kitchenLoading');
        const empty = document.getElementById('kitchenEmpty');

        if (!grid) return;
        if (loading) loading.style.display = 'none';

        if (!orders || orders.length === 0) {
            grid.innerHTML = '';
            if (empty) empty.style.display = '';
            return;
        }

        if (empty) empty.style.display = 'none';

        grid.innerHTML = orders.map(order => this.renderOrderCard(order)).join('');
    },

    renderOrderCard(order) {
        const statusClass = order.status;
        const statusLabel = {
            'new': '🆕 NOUVELLE',
            'preparing': '👨‍🍳 EN PREPARATION',
            'ready': '✅ PRÊTE'
        }[order.status] || order.status.toUpperCase();

        const elapsed = this.getElapsedTime(order.created_at);

        const itemsHtml = (order.items || []).map(item => {
            const opts = (() => {
                try {
                    const parsed = JSON.parse(item.options_json || '[]');
                    return Array.isArray(parsed) ? parsed : [];
                } catch { return []; }
            })();

            return `
                <div class="kds-item">
                    <div>
                        <span class="kds-item__qty">${item.quantity}x</span>
                        ${this.escapeHtml(item.product_name)}
                        ${opts.length > 0 ? `<span class="kds-item__options">${opts.map(o => this.escapeHtml(o)).join(', ')}</span>` : ''}
                    </div>
                </div>
            `;
        }).join('');

        const buttonsHtml = this.getStatusButtons(order);

        return `
            <div class="kds-card kds-card--${statusClass}">
                <div class="kds-card__header">
                    <span class="kds-card__number">#${this.escapeHtml(order.order_number)}</span>
                    <span class="kds-card__time">${elapsed}</span>
                </div>
                <div class="kds-card__body">
                    <div class="kds-card__restaurant">📍 ${this.escapeHtml(order.restaurant_name || '')}</div>
                    <div class="kds-card__status">${statusLabel}</div>
                    ${itemsHtml}
                    ${order.notes ? `<div class="kds-card__notes">📝 ${this.escapeHtml(order.notes)}</div>` : ''}
                </div>
                <div class="kds-card__footer">
                    ${buttonsHtml}
                </div>
            </div>
        `;
    },

    getStatusButtons(order) {
        const id = order.id;
        switch (order.status) {
            case 'new':
                return `<button class="kds-btn--preparing" onclick="Kitchen.updateStatus(${id}, 'preparing')" style="flex:1">👨‍🍳 Commencer</button>`;
            case 'preparing':
                return `<button class="kds-btn--ready" onclick="Kitchen.updateStatus(${id}, 'ready')" style="flex:1">✅ Prête</button>`;
            case 'ready':
                return `<button class="kds-btn--done" onclick="Kitchen.updateStatus(${id}, 'completed')" style="flex:1">🏁 Terminée</button>`;
            default:
                return '';
        }
    },

    // ── Update Order Status ──────────────────────────────────
    async updateStatus(orderId, status) {
        try {
            const res = await fetch(this.baseUrl + '/api/kitchen/status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    order_id: orderId,
                    status: status,
                    _csrf_token: this.csrfToken
                })
            });

            const data = await res.json();
            if (data.success) {
                this.fetchOrders(); // Refresh immediately
            }
        } catch (err) {
            console.error('Status update error:', err);
        }
    },

    // ── Helpers ──────────────────────────────────────────────
    getElapsedTime(createdAt) {
        const created = new Date(createdAt.replace(' ', 'T'));
        const now = new Date();
        const diffMs = now - created;
        const diffMin = Math.floor(diffMs / 60000);

        if (diffMin < 1) return 'À l\'instant';
        if (diffMin < 60) return `${diffMin} min`;
        const hours = Math.floor(diffMin / 60);
        const mins = diffMin % 60;
        return `${hours}h${mins.toString().padStart(2, '0')}`;
    },

    playNotification() {
        const sound = document.getElementById('notifSound');
        if (sound) {
            sound.currentTime = 0;
            sound.play().catch(() => {});
        }
    },

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = String(text);
        return div.innerHTML;
    }
};

document.addEventListener('DOMContentLoaded', () => Kitchen.init());
