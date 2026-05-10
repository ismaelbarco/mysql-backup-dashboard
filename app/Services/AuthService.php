<?php
declare(strict_types=1);
namespace App\Services;
use App\Models\User;

final class AuthService
{
    public static function login(string $email, string $password): bool
    {
        $user = User::findByEmail(filter_var($email, FILTER_VALIDATE_EMAIL) ?: '');
        if (!$user || !password_verify($password, $user['password'])) return false;
        session_regenerate_id(true);
        $_SESSION['user']=['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email'],'role'=>$user['role']];
        return true;
    }
    public static function logout(): void { $_SESSION=[]; session_destroy(); }
}
