<?php
require_once __DIR__ . '/../app/bootstrap.php';
use App\Security\Csrf; use App\Services\AuthService;
$error='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    if (!Csrf::verify($_POST['_csrf'] ?? null)) $error='Invalid CSRF token.';
    elseif (!AuthService::login(trim($_POST['email'] ?? ''), $_POST['password'] ?? '')) $error='Invalid credentials.';
    else { header('Location: /dashboard.php'); exit; }
}
?>
<!doctype html><html><head><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-[#F5E9DA] min-h-screen flex items-center justify-center"><div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md"><h1 class="text-2xl font-semibold text-[#2B2B2B] mb-4">Luxury Fitness Login</h1><?php if($error):?><p class="text-red-600 mb-3"><?=htmlspecialchars($error)?></p><?php endif;?><form method="POST" class="space-y-4"><input type="hidden" name="_csrf" value="<?=htmlspecialchars(Csrf::token())?>"><input class="w-full border p-3 rounded" type="email" name="email" required><input class="w-full border p-3 rounded" type="password" name="password" required><button class="w-full bg-[#C9A227] hover:bg-[#A67C00] text-white p-3 rounded">Login</button></form></div></body></html>
