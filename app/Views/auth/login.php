<section class="section">
    <div class="container">
        <div class="auth-card">
            <div class="auth-card__header">
                <span class="auth-card__icon">🍔</span>
                <h1>Connexion</h1>
                <p>Connectez-vous à votre compte Billy's</p>
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

            <form method="POST" action="<?= $baseUrl ?>/login" class="auth-form">
                <?= $csrf ?>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                           placeholder="votre@email.com" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password"
                           placeholder="Votre mot de passe" required minlength="6">
                </div>
                <button type="submit" class="btn btn--primary btn--lg btn--full">Se connecter</button>
            </form>

            <div class="auth-card__footer">
                <p>Pas encore de compte ? <a href="<?= $baseUrl ?>/register">Créer un compte</a></p>
            </div>

            <div class="auth-card__demo">
                <p><strong>Comptes de démonstration :</strong></p>
                <small>Admin : admin@billys.com / password</small><br>
                <small>Cuisine : cuisine@billys.com / password</small><br>
                <small>Client : client@billys.com / password</small>
            </div>
        </div>
    </div>
</section>
