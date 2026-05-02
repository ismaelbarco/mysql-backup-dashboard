<?php
require_once __DIR__ . '/../app/bootstrap.php';
use App\Middleware\AuthMiddleware; use App\Services\Database;
AuthMiddleware::requireLogin(); header('Content-Type: application/json');
$input = json_decode(file_get_contents('php://input'), true) ?: [];
$classId = filter_var($input['class_id'] ?? null, FILTER_VALIDATE_INT);
if (!$classId) { http_response_code(422); echo json_encode(['error'=>'Invalid class']); exit; }
$q=Database::connection()->prepare('INSERT INTO class_registrations(user_id,class_id,status) VALUES(?,?,"pending") ON DUPLICATE KEY UPDATE status="pending"');
$q->execute([$_SESSION['user']['id'],$classId]);
echo json_encode(['ok'=>true]);
