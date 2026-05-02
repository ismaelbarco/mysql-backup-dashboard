<?php
declare(strict_types=1);
namespace App\Models;
use App\Services\Database;

final class FitnessClass
{
    public static function allUpcoming(): array { $q=Database::connection()->prepare('SELECT c.*,u.name instructor_name FROM classes c LEFT JOIN users u ON u.id=c.instructor_id WHERE c.start_time >= NOW() ORDER BY c.start_time');$q->execute();return $q->fetchAll(); }
    public static function create(array $d): bool { $q=Database::connection()->prepare('INSERT INTO classes(title,description,instructor_id,start_time,end_time,capacity,price) VALUES(?,?,?,?,?,?,?)'); return $q->execute([$d['title'],$d['description'],$d['instructor_id'],$d['start_time'],$d['end_time'],$d['capacity'],$d['price']]); }
}
