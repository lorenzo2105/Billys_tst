<div class="kitchen-grid" id="kitchenGrid">
    <!-- Orders will be loaded dynamically via AJAX -->
    <div class="kitchen-loading" id="kitchenLoading">
        <span class="loading-spinner"></span>
        <p>Chargement des commandes...</p>
    </div>
</div>

<div class="kitchen-empty" id="kitchenEmpty" style="display:none;">
    <span class="empty-state__icon">✅</span>
    <h2>Aucune commande en cours</h2>
    <p>Les nouvelles commandes apparaîtront automatiquement ici.</p>
</div>

<!-- Audio notification -->
<audio id="notifSound" preload="auto">
    <source src="data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgip2teleGgoOIjH1sXF5tkKKcimFBOVd5kpuSe2RZWGd9jo+HdGVdX2uAi4d/cmdhYWx5goF9cWpmZm11fHx4cGxpanF2d3RwbWtrbXJ0dHFubWxscHJ0" type="audio/wav">
</audio>
