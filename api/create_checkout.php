<?php
require_once __DIR__ . '/../app/bootstrap.php';
use App\Middleware\AuthMiddleware; use App\Services\Database; use App\Services\StripeService;
AuthMiddleware::requireLogin(); header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$classId = filter_var($input['class_id'] ?? null, FILTER_VALIDATE_INT);
if(!$classId){http_response_code(422);echo json_encode(['error'=>'Invalid class']);exit;}
$qc=Database::connection()->prepare('SELECT id,price,capacity,(SELECT COUNT(*) FROM class_registrations r WHERE r.class_id=classes.id AND r.status IN ("pending","paid")) booked FROM classes WHERE id=? LIMIT 1');
$qc->execute([$classId]);$class=$qc->fetch();
if(!$class || $class['booked'] >= $class['capacity']){http_response_code(409);echo json_encode(['error'=>'Class full or missing']);exit;}
Database::connection()->prepare('INSERT INTO class_registrations(user_id,class_id,status) VALUES(?,?,"pending") ON DUPLICATE KEY UPDATE status="pending"')->execute([$_SESSION['user']['id'],$classId]);
$url=StripeService::createCheckoutSession((int)$classId,(int)$_SESSION['user']['id'],(int)round(((float)$class['price'])*100));
echo json_encode(['url'=>$url]);
