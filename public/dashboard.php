<?php
require_once __DIR__ . '/../app/bootstrap.php';
use App\Middleware\AuthMiddleware; use App\Models\FitnessClass;
AuthMiddleware::requireLogin();
if (($_SESSION['user']['role'] ?? '') === 'admin') { header('Location: /admin/dashboard.php'); exit; }
$classes = FitnessClass::allUpcoming();
?><!doctype html><html><head><script src="https://cdn.tailwindcss.com"></script></head><body class="bg-[#F5E9DA] p-6"><div class="max-w-6xl mx-auto"><h1 class="text-3xl mb-6">Welcome, <?=htmlspecialchars($_SESSION['user']['name'])?></h1><div class="grid md:grid-cols-3 gap-4"><div class="bg-white p-4 rounded shadow">Today's Class: <?=!empty($classes)?htmlspecialchars($classes[0]['title']):'None'?></div><div class="bg-white p-4 rounded shadow">Upcoming Classes: <?=count($classes)?></div><div class="bg-white p-4 rounded shadow">Messages: 0</div></div><a class="inline-block mt-5 bg-[#C9A227] text-white px-4 py-2 rounded" href="/calendar.php">Open Calendar</a></div></body></html>
