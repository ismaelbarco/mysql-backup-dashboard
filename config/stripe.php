<?php
declare(strict_types=1);

return [
    'secret_key' => getenv('STRIPE_SECRET_KEY') ?: '',
    'publishable_key' => getenv('STRIPE_PUBLISHABLE_KEY') ?: '',
    'webhook_secret' => getenv('STRIPE_WEBHOOK_SECRET') ?: '',
];
