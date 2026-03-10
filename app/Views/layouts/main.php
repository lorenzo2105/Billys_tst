<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? "Billy's Fast Food") ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header" id="header">
        <div class="container header__inner">
            <a href="<?= $baseUrl ?>/" class="header__logo">
                <span class="logo-icon">🍔</span>
                <span class="logo-text">Billy's</span>
            </a>

            <nav class="header__nav" id="mainNav">
                <a href="<?= $baseUrl ?>/" class="nav-link">Accueil</a>
                <a href="<?= $baseUrl ?>/menu" class="nav-link">Menu</a>
                <?php if (\App\Core\Auth::check()): ?>
                    <a href="<?= $baseUrl ?>/account/orders" class="nav-link">Mes Commandes</a>
                <?php endif; ?>
            </nav>

            <div class="header__actions">
                <button class="cart-btn" id="cartBtn" onclick="window.location.href='<?= $baseUrl ?>/cart'">
                    <span class="cart-icon">🛒</span>
                    <span class="cart-badge" id="cartBadge">0</span>
                </button>

                <?php if (\App\Core\Auth::check()): ?>
                    <div class="user-menu">
                        <button class="user-btn" id="userMenuBtn">
                            <span>👤</span>
                            <span class="user-name"><?= htmlspecialchars(\App\Core\Auth::user()['name'] ?? '') ?></span>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="<?= $baseUrl ?>/account">Mon Compte</a>
                            <a href="<?= $baseUrl ?>/account/orders">Mes Commandes</a>
                            <?php if (\App\Core\Auth::isAdmin()): ?>
                                <a href="<?= $baseUrl ?>/admin">Administration</a>
                            <?php endif; ?>
                            <?php if (\App\Core\Auth::isKitchen() || \App\Core\Auth::isAdmin()): ?>
                                <a href="<?= $baseUrl ?>/kitchen">Cuisine</a>
                            <?php endif; ?>
                            <hr>
                            <a href="<?= $baseUrl ?>/logout" class="text-danger">Déconnexion</a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= $baseUrl ?>/login" class="btn btn--sm btn--outline">Connexion</a>
                <?php endif; ?>

                <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php $flash_success = \App\Core\Session::getFlash('success'); ?>
    <?php $flash_error = \App\Core\Session::getFlash('error'); ?>
    <?php if ($flash_success): ?>
        <div class="alert alert--success" id="flashAlert"><?= htmlspecialchars($flash_success) ?> <button onclick="this.parentElement.remove()">&times;</button></div>
    <?php endif; ?>
    <?php if ($flash_error): ?>
        <div class="alert alert--error" id="flashAlert"><?= htmlspecialchars($flash_error) ?> <button onclick="this.parentElement.remove()">&times;</button></div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="main">
        <?= $pageContent ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__grid">
                <div class="footer__brand">
                    <span class="logo-icon">🍔</span>
                    <span class="logo-text">Billy's Fast Food</span>
                    <p>Les meilleurs burgers de la ville, préparés avec passion.</p>
                </div>
                <div class="footer__links">
                    <h4>Navigation</h4>
                    <a href="<?= $baseUrl ?>/">Accueil</a>
                    <a href="<?= $baseUrl ?>/menu">Menu</a>
                    <a href="<?= $baseUrl ?>/cart">Panier</a>
                </div>
                <div class="footer__links">
                    <h4>Compte</h4>
                    <a href="<?= $baseUrl ?>/login">Connexion</a>
                    <a href="<?= $baseUrl ?>/register">Inscription</a>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; <?= date('Y') ?> Billy's Fast Food. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        window.APP = {
            baseUrl: '<?= $baseUrl ?>',
            csrfToken: '<?= \App\Core\CSRF::generateToken() ?>',
            isLoggedIn: <?= \App\Core\Auth::check() ? 'true' : 'false' ?>
        };
    </script>
    <script src="<?= $baseUrl ?>/assets/js/app.js"></script>
</body>
</html>
