<?php
declare(strict_types=1);
namespace App\Services;

use Stripe\Checkout\Session;
use Stripe\Stripe;

final class StripeService
{
    public static function createCheckoutSession(int $classId, int $userId, int $amountCents): string
    {
        $cfg = require __DIR__ . '/../../config/stripe.php';
        $app = require __DIR__ . '/../../config/app.php';
        Stripe::setApiKey($cfg['secret_key']);
        $session = Session::create([
            'mode' => 'payment',
            'line_items' => [[
                'quantity' => 1,
                'price_data' => ['currency'=>'usd','unit_amount'=>$amountCents,'product_data'=>['name'=>'Luxury Fitness Class']]
            ]],
            'metadata' => ['class_id'=>$classId,'user_id'=>$userId],
            'success_url' => $app['base_url'].'/api/verify_payment.php?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $app['base_url'].'/calendar.php',
        ]);
        return $session->url;
    }
}
