<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuisine KDS - Billy's</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/kitchen.css">
    <meta http-equiv="refresh" content="300">
</head>
<body class="kitchen-body">
    <header class="kitchen-header">
        <div class="kitchen-header__left">
            <span class="logo-icon">👨‍🍳</span>
            <h1>Billy's - Cuisine</h1>
        </div>
        <div class="kitchen-header__center">
            <span class="kitchen-clock" id="kitchenClock"></span>
        </div>
        <div class="kitchen-header__right">
            <select id="restaurantFilter" class="kitchen-select">
                <option value="">Tous les restaurants</option>
                <?php foreach ($restaurants ?? [] as $r): ?>
                    <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <a href="<?= $baseUrl ?>/admin" class="btn btn--sm">Admin</a>
            <a href="<?= $baseUrl ?>/logout" class="btn btn--sm btn--danger">Quitter</a>
        </div>
    </header>

    <main class="kitchen-main">
        <?= $pageContent ?>
    </main>

    <script>
        window.APP = {
            baseUrl: '<?= $baseUrl ?>',
            csrfToken: '<?= \App\Core\CSRF::generateToken() ?>'
        };
    </script>
    <script src="<?= $baseUrl ?>/assets/js/kitchen.js"></script>
</body>
</html>
