<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function purchase()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'T-shirt',
                'description' => 'Comfortable cotton t-shirt',
                'amount' => 500,
                'currency' => 'usd',
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'payment_intent_data' => [
                'metadata' => [
                    'order_id' => 123,
                    'user_id' => 12312,
                    'task_id' => 623
                ]
            ],
            'success_url' => 'http://darestreams.local/api/stripe/success?session_id={CHECKOUT_SESSION_ID}&user_id=123',
            'cancel_url' => 'http://darestreams.local/api/stripe/fail',
        ]);

        //dd($session);

        //darestreams.local/api/stripe/purchase
        //cs_test_ZuXV17Gxd8SpcGL6yjXuu5TzhWrVDA2MgpKP4o3hcrQLsRBgS9CPX283

        return response()->view('payments.stripe', [
            'session_id' => $session['id'],
            'key' => config('services.stripe.key')
        ], 200);
    }

    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $data = \Stripe\Checkout\Session::retrieve($request->get('session_id'));



        $p = \Stripe\PaymentIntent::retrieve(
            $data['payment_intent']
        );

        dd($p);

        if($p['status']=='succeeded')
        {
            echo "Yahoo status";
        }

        dd($p);
    }

    public function fail(Request $request)
    {
        dd($request->all());
    }
}