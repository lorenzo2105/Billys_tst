<?php
/**
 * @var array $supplements
 * @var string $baseUrl
 * @var string $csrf
 */
?>

<div class="admin-content">
    <div class="admin-card">
        <div class="admin-card__header">
            <h2>🍟 Gestion des Suppléments</h2>
            <button class="btn btn--primary" onclick="document.getElementById('addSupplementForm').style.display='block'">
                ➕ Nouveau supplément
            </button>
        </div>

        <!-- Add supplement form (hidden by default) -->
        <div id="addSupplementForm" style="display:none;padding:1.5rem;border-bottom:1px solid var(--border);">
            <h3 style="margin-bottom:1rem">Ajouter un nouveau supplément</h3>
            <form method="POST" action="<?= $baseUrl ?>/admin/supplements/store">
                <?= $csrf ?>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="new_name">Nom du supplément *</label>
                        <input type="text" id="new_name" name="name" required placeholder="ex: Bacon">
                    </div>
                    <div class="form-group">
                        <label for="new_price">Prix</label>
                        <input type="number" id="new_price" name="price" step="1" min="0" value="0" required>
                    </div>
                </div>
                <div class="form-actions" style="margin-top:1rem">
                    <button type="submit" class="btn btn--primary">💾 Créer</button>
                    <button type="button" class="btn btn--outline" onclick="document.getElementById('addSupplementForm').style.display='none'">Annuler</button>
                </div>
            </form>
        </div>

        <!-- Supplements list -->
        <div class="admin-card__section">
            <?php if (empty($supplements)): ?>
                <p class="text-muted">Aucun supplément configuré.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Prix</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($supplements as $supp): ?>
                            <tr>
                                <td>
                                    <form method="POST" action="<?= $baseUrl ?>/admin/supplements/update/<?= $supp['id'] ?>" style="display:inline">
                                        <?= $csrf ?>
                                        <input type="text" name="name" value="<?= htmlspecialchars($supp['name']) ?>" 
                                               style="width:250px;padding:.375rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--bg-gray);color:var(--text);">
                                </td>
                                <td>
                                    <input type="number" name="price" value="<?= (float)$supp['price'] ?>" 
                                           step="1" min="0"
                                           style="width:120px;padding:.375rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--bg-gray);color:var(--text);">
                                </td>
                                <td>
                                    <select name="is_active" style="padding:.375rem;border:1px solid var(--border);border-radius:var(--radius-sm);background:var(--bg-gray);color:var(--text);">
                                        <option value="1" <?= $supp['is_active'] ? 'selected' : '' ?>>✅ Actif</option>
                                        <option value="0" <?= !$supp['is_active'] ? 'selected' : '' ?>>❌ Inactif</option>
                                    </select>
                                </td>
                                <td style="white-space:nowrap">
                                        <button type="submit" class="btn btn--sm btn--primary">💾 Sauver</button>
                                    </form>
                                    <form method="POST" action="<?= $baseUrl ?>/admin/supplements/delete/<?= $supp['id'] ?>"
                                          style="display:inline;margin-left:.25rem" onsubmit="return confirm('Supprimer ce supplément ? Il sera retiré de tous les produits.')">
                                        <?= $csrf ?>
                                        <button type="submit" class="btn btn--sm btn--danger">🗑️</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
