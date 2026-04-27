<?php
session_start();

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

chdir(BASE_PATH);

// Load application configuration (DB credentials, Momo keys, app settings)
require_once BASE_PATH . '/config/database.php';

// Simple Autoloader
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/core/',
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        BASE_PATH . '/models/',
        BASE_PATH . '/helpers/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Initialize Logger
if (class_exists('Logger')) {
    Logger::init(BASE_PATH . '/logs');
}

require_once APP_PATH . '/core/App.php';

$app = new App();
$app->run();
