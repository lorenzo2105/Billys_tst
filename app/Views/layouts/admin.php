<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> - Billy's Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/admin.css">
</head>
<body class="admin-body">
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar__header">
            <a href="<?= $baseUrl ?>/admin" class="sidebar__logo">
                <span>🍔</span> Billy's Admin
            </a>
        </div>
        <nav class="sidebar__nav">
            <a href="<?= $baseUrl ?>/admin" class="sidebar__link <?= str_contains($_SERVER['REQUEST_URI'], '/admin') && !str_contains($_SERVER['REQUEST_URI'], '/admin/') ? 'active' : '' ?>">
                <span>📊</span> Tableau de bord
            </a>
            <a href="<?= $baseUrl ?>/admin/products" class="sidebar__link <?= str_contains($_SERVER['REQUEST_URI'], '/products') || str_contains($_SERVER['REQUEST_URI'], '/product') ? 'active' : '' ?>">
                <span>🍔</span> Produits
            </a>
            <a href="<?= $baseUrl ?>/admin/categories" class="sidebar__link <?= str_contains($_SERVER['REQUEST_URI'], '/categories') || str_contains($_SERVER['REQUEST_URI'], '/category') ? 'active' : '' ?>">
                <span>📁</span> Catégories
            </a>
            <a href="<?= $baseUrl ?>/admin/restaurants" class="sidebar__link <?= str_contains($_SERVER['REQUEST_URI'], '/restaurants') || str_contains($_SERVER['REQUEST_URI'], '/restaurant') ? 'active' : '' ?>">
                <span>🏪</span> Restaurants
            </a>
            <a href="<?= $baseUrl ?>/admin/orders" class="sidebar__link <?= str_contains($_SERVER['REQUEST_URI'], '/orders') || str_contains($_SERVER['REQUEST_URI'], '/order/') ? 'active' : '' ?>">
                <span>📋</span> Commandes
            </a>
            <a href="<?= $baseUrl ?>/admin/supplements" class="sidebar__link <?= str_contains($_SERVER['REQUEST_URI'], '/supplements') ? 'active' : '' ?>">
                <span>🍟</span> Suppléments
            </a>
            <hr>
            <a href="<?= $baseUrl ?>/kitchen" class="sidebar__link">
                <span>👨‍🍳</span> Cuisine
            </a>
            <a href="<?= $baseUrl ?>/" class="sidebar__link">
                <span>🌐</span> Voir le site
            </a>
            <a href="<?= $baseUrl ?>/logout" class="sidebar__link text-danger">
                <span>🚪</span> Déconnexion
            </a>
        </nav>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <button class="hamburger" id="adminHamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
            <h1 class="admin-topbar__title"><?= htmlspecialchars($pageTitle ?? 'Admin') ?></h1>
            <div class="admin-topbar__user">
                <span>👤 <?= htmlspecialchars(\App\Core\Auth::user()['name'] ?? 'Admin') ?></span>
            </div>
        </header>

        <?php $flash_success = \App\Core\Session::getFlash('success'); ?>
        <?php $flash_error = \App\Core\Session::getFlash('error'); ?>
        <?php if ($flash_success): ?>
            <div class="alert alert--success"><?= htmlspecialchars($flash_success) ?> <button onclick="this.parentElement.remove()">&times;</button></div>
        <?php endif; ?>
        <?php if ($flash_error): ?>
            <div class="alert alert--error"><?= htmlspecialchars($flash_error) ?> <button onclick="this.parentElement.remove()">&times;</button></div>
        <?php endif; ?>

        <div class="admin-content">
            <?= $pageContent ?>
        </div>
    </div>

    <script>
        window.APP = {
            baseUrl: '<?= $baseUrl ?>',
            csrfToken: '<?= \App\Core\CSRF::generateToken() ?>'
        };
        // Sidebar toggle
        document.getElementById('adminHamburger')?.addEventListener('click', () => {
            document.getElementById('adminSidebar').classList.toggle('open');
        });
    </script>
    <script src="<?= $baseUrl ?>/assets/js/admin.js"></script>
</body>
</html>
