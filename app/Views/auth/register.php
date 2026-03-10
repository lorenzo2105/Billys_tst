<section class="section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-card__header">
                <span class="auth-card__icon">🍔</span>
                <h1>Inscription</h1>
                <p>Créez votre compte Billy's</p>
            </div>

            <?php $errors = \App\Core\Session::getFlash('errors') ?? []; ?>
            <?php $old = \App\Core\Session::getFlash('old') ?? []; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert--error">
                    <?php foreach ($errors as $field => $msgs): ?>
                        <?php foreach ($msgs as $msg): ?>
                            <p><?= htmlspecialchars($msg) ?></p>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= $baseUrl ?>/register" class="auth-form">
                <?= $csrf ?>
                <div class="form-group">
                    <label for="name">Nom complet</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                           placeholder="Jean Dupont" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                           placeholder="votre@email.com" required>
                </div>
                <div class="form-group">
                    <label for="phone">Téléphone (optionnel)</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($old['phone'] ?? '') ?>"
                           placeholder="06 12 34 56 78">
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           placeholder="Minimum 6 caractères" required minlength="6">
                </div>
                <div class="form-group">
                    <label for="password_confirm">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirm" name="password_confirm"
                           placeholder="Confirmez votre mot de passe" required minlength="6">
                </div>
                <button type="submit" class="btn btn--primary btn--lg btn--full">Créer mon compte</button>
            </form>

            <div class="auth-card__footer">
                <p>Déjà un compte ? <a href="<?= $baseUrl ?>/login">Se connecter</a></p>
            </div>
        </div>
    </div>
</section>
