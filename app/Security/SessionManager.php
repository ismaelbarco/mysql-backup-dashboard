<?php
declare(strict_types=1);

namespace App\Security;

final class SessionManager
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) return;
        session_set_cookie_params(['lifetime'=>0,'path'=>'/','httponly'=>true,'secure'=>!empty($_SERVER['HTTPS']),'samesite'=>'Lax']);
        session_start();
        $cfg = require __DIR__ . '/../../config/app.php';
        $now = time();
        if (isset($_SESSION['last_activity']) && ($now - (int)$_SESSION['last_activity']) > $cfg['session_timeout']) {
            session_unset(); session_destroy(); session_start();
        }
        $_SESSION['last_activity'] = $now;
    }
}
