<?php require_once __DIR__ . '/../app/bootstrap.php'; App\Services\AuthService::logout(); header('Location: /login.php');
