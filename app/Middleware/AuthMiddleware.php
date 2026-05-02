<?php
declare(strict_types=1);

namespace App\Middleware;

final class AuthMiddleware
{
    public static function requireLogin(): void
    {
        if (empty($_SESSION['user'])) { header('Location: /login.php'); exit; }
    }

    public static function requireRole(string $role): void
    {
        self::requireLogin();
        if (($_SESSION['user']['role'] ?? '') !== $role) { http_response_code(403); exit('Forbidden'); }
    }
}
