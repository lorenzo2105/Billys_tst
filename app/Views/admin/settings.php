<?php
/**
 * @var array $settings
 * @var string $baseUrl
 * @var string $csrf
 */
?>

<div class="admin-content">
    <div class="admin-card">
        <div class="admin-card__header">
            <h2>⚙️ Paramètres de l'application</h2>
        </div>

        <form method="POST" action="<?= $baseUrl ?>/admin/settings/update" class="admin-form">
            <?= $csrf ?>

            <div class="admin-card__section">
                <h3>🍔 Prix des Burgers</h3>
                <p style="color: var(--text-muted); font-size: .9rem; margin-bottom: 1.5rem;">
                    Ces prix sont appliqués automatiquement à tous les burgers avec options viande.
                </p>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="burger_price_simple">
                            🥩 Prix Burger Simple (1 viande)
                        </label>
                        <input type="number" 
                               id="burger_price_simple" 
                               name="burger_price_simple" 
                               step="10" 
                               min="0"
                               value="<?= htmlspecialchars($settings['burger_price_simple']['value'] ?? '0') ?>"
                               required>
                        <small style="color: var(--text-muted);">Prix de base (généralement 0 XPF)</small>
                    </div>

                    <div class="form-group">
                        <label for="burger_price_double">
                            🥩🥩 Prix Burger Double (2 viandes)
                        </label>
                        <input type="number" 
                               id="burger_price_double" 
                               name="burger_price_double" 
                               step="10" 
                               min="0"
                               value="<?= htmlspecialchars($settings['burger_price_double']['value'] ?? '360') ?>"
                               required>
                        <small style="color: var(--text-muted);">Supplément pour double viande (ex: 360 XPF)</small>
                    </div>
                </div>

                <div style="background: var(--bg-gray); padding: 1rem; border-radius: var(--radius-sm); margin-top: 1rem;">
                    <p style="font-size: .85rem; color: var(--text-light); margin: 0;">
                        💡 <strong>Info :</strong> Ces prix seront automatiquement appliqués à tous les burgers. 
                        Le prix affiché au client sera : <strong>Prix du burger + Prix de la viande sélectionnée</strong>
                    </p>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn--primary btn--lg">
                    💾 Enregistrer les paramètres
                </button>
            </div>
        </form>
    </div>
</div>
