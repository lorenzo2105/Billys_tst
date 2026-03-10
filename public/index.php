<?php
declare(strict_types=1);

/**
 * Billy's Fast Food - Entry Point
 * All requests are routed through this file.
 */

define('BASE_PATH', dirname(__DIR__));

// Autoloader
require_once BASE_PATH . '/app/Core/Autoloader.php';

// Load helpers
require_once BASE_PATH . '/app/Helpers/functions.php';

// Load environment
$envLoader = new App\Core\EnvLoader(BASE_PATH . '/.env');
$envLoader->load();

// Boot application
$app = new App\Core\App();
$app->run();
