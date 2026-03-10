<div class="admin-card">
    <div class="admin-card__header">
        <h2>Points de Vente (<?= count($restaurants) ?>)</h2>
    </div>

    <?php foreach ($restaurants as $r): ?>
    <div class="admin-card__section">
        <form method="POST" action="<?= $baseUrl ?>/admin/restaurant/update/<?= $r['id'] ?>" class="admin-form">
            <?= $csrf ?>
            <h3>🏪 Restaurant #<?= $r['id'] ?></h3>
            <div class="form-grid">
                <div class="form-group">
                    <label>Nom</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($r['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Adresse</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($r['address']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Téléphone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($r['phone'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Horaires</label>
                    <input type="text" name="opening_hours" value="<?= htmlspecialchars($r['opening_hours'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" value="1" <?= $r['is_active'] ? 'checked' : '' ?>>
                        Restaurant actif
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn--primary btn--sm">Enregistrer</button>
        </form>
    </div>
    <?php endforeach; ?>
</div>
