<?php
require_once __DIR__ . '/../app/bootstrap.php';
use App\Models\FitnessClass;
header('Content-Type: application/json');
$events = array_map(fn($c)=>['id'=>$c['id'],'title'=>$c['title'],'start'=>$c['start_time'],'end'=>$c['end_time'],'price'=>$c['price']], FitnessClass::allUpcoming());
echo json_encode($events, JSON_THROW_ON_ERROR);
