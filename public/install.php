<?php
/**
 * Billy's Fast Food - Installation Helper
 * Access this file once to create the database and seed data.
 * DELETE THIS FILE AFTER INSTALLATION for security.
 */

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/app/Core/Autoloader.php';
$envLoader = new App\Core\EnvLoader(BASE_PATH . '/.env');
$envLoader->load();

$host = $_ENV['DB_HOST'] ?? '127.0.0.1';
$port = $_ENV['DB_PORT'] ?? '3306';
$db   = $_ENV['DB_DATABASE'] ?? 'billys_fastfood';
$user = $_ENV['DB_USERNAME'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';

$step = $_GET['step'] ?? 'check';
$messages = [];
$errors = [];

// ── Step: Check requirements ────────────────────────────────
if ($step === 'check') {
    $checks = [
        'PHP 8.0+' => version_compare(PHP_VERSION, '8.0.0', '>='),
        'PDO MySQL' => extension_loaded('pdo_mysql'),
        'mbstring' => extension_loaded('mbstring'),
        'Session' => extension_loaded('session'),
        'Uploads dir writable' => is_writable(BASE_PATH . '/public/assets/uploads/') || @mkdir(BASE_PATH . '/public/assets/uploads/', 0755, true),
        'Storage dir writable' => is_writable(BASE_PATH . '/storage/') || @mkdir(BASE_PATH . '/storage/logs/', 0755, true),
    ];
}

// ── Step: Install database ──────────────────────────────────
if ($step === 'install') {
    try {
        // Connect without database first
        $pdo = new PDO("mysql:host={$host};port={$port};charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);

        // Read and execute SQL
        $sqlFile = BASE_PATH . '/storage/migrations/001_create_database.sql';
        if (!file_exists($sqlFile)) {
            $errors[] = "Fichier SQL introuvable: {$sqlFile}";
        } else {
            $sql = file_get_contents($sqlFile);

            // Split by semicolons (handle multi-statement)
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$db}`");

            // Execute statements one by one
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            $executed = 0;
            foreach ($statements as $stmt) {
                if (empty($stmt) || stripos($stmt, 'CREATE DATABASE') === 0 || stripos($stmt, 'USE ') === 0) {
                    continue;
                }
                try {
                    $pdo->exec($stmt);
                    $executed++;
                } catch (PDOException $e) {
                    // Ignore duplicate entries on re-run
                    if ($e->getCode() != '23000') {
                        $errors[] = "SQL Error: " . $e->getMessage();
                    }
                }
            }

            // Generate proper password hashes
            $passwordHash = password_hash('password', PASSWORD_DEFAULT);
            $pdo->exec("UPDATE users SET password = '{$passwordHash}' WHERE email = 'admin@billys.com'");
            $pdo->exec("UPDATE users SET password = '{$passwordHash}' WHERE email = 'cuisine@billys.com'");
            $pdo->exec("UPDATE users SET password = '{$passwordHash}' WHERE email = 'client@billys.com'");

            if (empty($errors)) {
                $messages[] = "Base de données créée avec succès ! ({$executed} requêtes exécutées)";
                $messages[] = "Mots de passe mis à jour avec password_hash().";
                $messages[] = "Tous les comptes utilisent le mot de passe : <strong>password</strong>";
            }
        }
    } catch (PDOException $e) {
        $errors[] = "Erreur de connexion: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Billy's Fast Food</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}
        body{font-family:'Segoe UI',sans-serif;background:#f1f3f5;color:#333;padding:2rem}
        .container{max-width:700px;margin:0 auto}
        h1{text-align:center;margin-bottom:2rem;font-size:1.8rem}
        .card{background:#fff;border-radius:12px;padding:2rem;box-shadow:0 2px 12px rgba(0,0,0,.08);margin-bottom:1.5rem}
        .card h2{margin-bottom:1rem;font-size:1.2rem}
        .check{padding:.5rem 0;display:flex;justify-content:space-between;border-bottom:1px solid #eee}
        .check:last-child{border:none}
        .ok{color:#06d6a0;font-weight:700}
        .fail{color:#ef476f;font-weight:700}
        .msg{padding:.75rem 1rem;border-radius:8px;margin-bottom:.75rem}
        .msg--success{background:#d4edda;color:#155724;border:1px solid #c3e6cb}
        .msg--error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb}
        .btn{display:inline-block;padding:.75rem 2rem;background:#ff6b35;color:#fff;text-decoration:none;border-radius:8px;font-weight:600;border:none;cursor:pointer;font-size:1rem;transition:background .2s}
        .btn:hover{background:#e55a2b}
        .btn--outline{background:transparent;color:#ff6b35;border:2px solid #ff6b35}
        .btn--outline:hover{background:#ff6b35;color:#fff}
        .center{text-align:center;margin-top:1.5rem}
        .warn{background:#fff3cd;color:#856404;padding:1rem;border-radius:8px;margin-top:1rem;border:1px solid #ffeaa7}
        code{background:#f1f3f5;padding:.125rem .375rem;border-radius:4px;font-size:.9em}
    </style>
</head>
<body>
<div class="container">
    <h1>🍔 Billy's Fast Food - Installation</h1>

    <?php if ($step === 'check'): ?>
    <div class="card">
        <h2>Vérification des prérequis</h2>
        <?php foreach ($checks as $name => $ok): ?>
        <div class="check">
            <span><?= $name ?></span>
            <span class="<?= $ok ? 'ok' : 'fail' ?>"><?= $ok ? '✅ OK' : '❌ Manquant' ?></span>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="card">
        <h2>Configuration actuelle (.env)</h2>
        <div class="check"><span>Hôte</span><span><code><?= htmlspecialchars($host) ?></code></span></div>
        <div class="check"><span>Base de données</span><span><code><?= htmlspecialchars($db) ?></code></span></div>
        <div class="check"><span>Utilisateur</span><span><code><?= htmlspecialchars($user) ?></code></span></div>
        <div class="check"><span>Port</span><span><code><?= htmlspecialchars($port) ?></code></span></div>
    </div>

    <?php if (!in_array(false, $checks, true)): ?>
    <div class="center">
        <a href="?step=install" class="btn">🚀 Installer la base de données</a>
    </div>
    <?php else: ?>
    <div class="warn">
        ⚠️ Certains prérequis ne sont pas remplis. Corrigez-les avant d'installer.
    </div>
    <?php endif; ?>

    <?php elseif ($step === 'install'): ?>

    <div class="card">
        <h2>Résultat de l'installation</h2>
        <?php foreach ($messages as $msg): ?>
            <div class="msg msg--success"><?= $msg ?></div>
        <?php endforeach; ?>
        <?php foreach ($errors as $err): ?>
            <div class="msg msg--error"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($errors)): ?>
    <div class="card">
        <h2>✅ Installation terminée !</h2>
        <p>Comptes disponibles :</p>
        <div class="check"><span>Admin</span><span><code>admin@billys.com</code> / <code>password</code></span></div>
        <div class="check"><span>Cuisine</span><span><code>cuisine@billys.com</code> / <code>password</code></span></div>
        <div class="check"><span>Client</span><span><code>client@billys.com</code> / <code>password</code></span></div>

        <div class="warn">
            ⚠️ <strong>IMPORTANT :</strong> Supprimez ce fichier <code>install.php</code> après l'installation pour des raisons de sécurité.
        </div>

        <div class="center">
            <a href="./" class="btn">🍔 Accéder au site</a>
            <a href="./login" class="btn btn--outline" style="margin-left:.5rem">Se connecter</a>
        </div>
    </div>
    <?php else: ?>
    <div class="center">
        <a href="?step=check" class="btn btn--outline">← Retour aux vérifications</a>
    </div>
    <?php endif; ?>

    <?php endif; ?>
</div>
</body>
</html>
