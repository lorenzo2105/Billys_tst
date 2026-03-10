<section class="section">
    <div class="container">
        <h1 class="section__title">Mon Compte</h1>

        <div class="account-grid">
            <div class="account-card">
                <h3>👤 Informations personnelles</h3>
                <div class="info-list">
                    <div class="info-item">
                        <span class="info-label">Nom</span>
                        <span class="info-value"><?= htmlspecialchars($user['name']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email</span>
                        <span class="info-value"><?= htmlspecialchars($user['email']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Téléphone</span>
                        <span class="info-value"><?= htmlspecialchars($user['phone'] ?? 'Non renseigné') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Membre depuis</span>
                        <span class="info-value"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                    </div>
                </div>
            </div>

            <div class="account-card">
                <h3>🔗 Raccourcis</h3>
                <div class="account-links">
                    <a href="<?= $baseUrl ?>/account/orders" class="account-link">
                        <span>📋</span> Mes commandes
                    </a>
                    <a href="<?= $baseUrl ?>/menu" class="account-link">
                        <span>🍔</span> Commander
                    </a>
                    <a href="<?= $baseUrl ?>/logout" class="account-link text-danger">
                        <span>🚪</span> Se déconnecter
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
