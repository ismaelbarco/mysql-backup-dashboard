<?php
declare(strict_types=1);
namespace App\Models;
use App\Services\Database;

final class User
{
    public static function findByEmail(string $email): ?array {
        $q=Database::connection()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');$q->execute([$email]);return $q->fetch() ?: null;
    }
    public static function countClients(): int {
        return (int)Database::connection()->query("SELECT COUNT(*) FROM users WHERE role='client'")->fetchColumn();
    }
}
