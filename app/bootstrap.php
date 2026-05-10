<?php
declare(strict_types=1);

use App\Security\Csrf;
use App\Security\SessionManager;

require_once __DIR__ . '/../vendor/autoload.php';

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $path = __DIR__ . '/' . str_replace('App\\', '', $class) . '.php';
    $path = str_replace('\\', '/', $path);
    if (file_exists($path)) require_once $path;
});

SessionManager::start();
Csrf::bootstrap();
