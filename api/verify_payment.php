<?php
require_once __DIR__ . '/../app/bootstrap.php';
use App\Services\Database;
use Stripe\Stripe; use Stripe\Checkout\Session;
$cfg=require __DIR__.'/../config/stripe.php'; Stripe::setApiKey($cfg['secret_key']);
$sessionId = $_GET['session_id'] ?? '';
if (!$sessionId) { http_response_code(400); exit('Missing session_id'); }
$session = Session::retrieve($sessionId);
if ($session->payment_status !== 'paid') { http_response_code(400); exit('Payment not confirmed'); }
$classId=(int)$session->metadata->class_id; $userId=(int)$session->metadata->user_id; $intent=(string)$session->payment_intent;
$pdo=Database::connection(); $pdo->beginTransaction();
$pdo->prepare('UPDATE class_registrations SET status="paid",stripe_payment_intent_id=? WHERE user_id=? AND class_id=?')->execute([$intent,$userId,$classId]);
$pdo->prepare('INSERT INTO payments(user_id,class_id,amount,stripe_payment_intent_id,status) VALUES(?,?,?,?,?)')->execute([$userId,$classId,((int)$session->amount_total)/100,$intent,'paid']);
$pdo->commit();
header('Location: /dashboard.php');
