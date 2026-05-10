<?php require_once __DIR__ . '/../app/bootstrap.php'; use App\Middleware\AuthMiddleware; AuthMiddleware::requireLogin(); ?>
<!doctype html><html><head><script src="https://cdn.tailwindcss.com"></script><link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet"><script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script></head>
<body class="bg-[#F5E9DA] p-5"><div class="max-w-6xl mx-auto"><div class="bg-white p-4 rounded shadow"><div id="calendar"></div></div></div>
<script>
document.addEventListener('DOMContentLoaded',()=>{new FullCalendar.Calendar(document.getElementById('calendar'),{initialView:'dayGridMonth',events:'/api/classes.php',eventClick:(info)=>{const c=info.event.extendedProps; if(confirm(`${info.event.title}\n$${c.price}\nRegister & Pay?`)){fetch('/api/create_checkout.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({class_id:info.event.id})}).then(r=>r.json()).then(d=>{if(d.url) location.href=d.url;});}},dateClick:(i)=>{if('<?= $_SESSION['user']['role'] ?>'==='admin') location.href='/admin/classes.php?date='+i.dateStr;}}).render();});
</script></body></html>
