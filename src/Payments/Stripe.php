<?php

namespace Dare\Payments;

use Dare\Contracts\PaymentInterface;
use Illuminate\Http\Request;

class Stripe implements PaymentInterface
{
    public function init()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function checkout(array $params)
    {
        $user_id = $params['user_id'];
        $task_id = $params['task_id'];

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'Donation',
                'description' => $params['description'],
                'amount' => $params['amount']*100,
                'currency' => $params['currency'],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'payment_intent_data' => [
                'metadata' => [
                    'order_id' => $params['transactionId'],
                    'user_id' => $user_id,
                    'task_id' => $task_id
                ]
            ],
            'success_url' => route('payment.checkout.completed', ['gateway' => 'stripe', 'user' => $user_id, 'task' => $task_id]),
            'cancel_url' => route('payment.checkout.cancelled', ['gateway' => 'paypal', 'user' => $user_id, 'task' => $task_id]),
        ]);

        if($session)
            $params['result']->update(['exid' => $session['id']]);

        return response()->view('payments.stripe', [
            'session_id' => $session['id'],
            'key' => config('services.stripe.key')
        ], 200);
    }

    public function completed(Request $request)
    {
        $data = \Stripe\Checkout\Session::retrieve($request->get('session_id'));

        if($data && isset($data['payment_intent']))
        {
            $intent = \Stripe\PaymentIntent::retrieve(
                $data['payment_intent']
            );

            if($intent && isset($intent['status']))
            {
                return [
                    'status' => $intent['status']=='succeeded' ? "completed" : "",
                    'data' => [
                        'intent' => $intent,
                        'session' => $data
                    ],
                    'order_id' => $intent['metadata']['order_id'],
                    'transaction_id' => $data['id'],
                    'amount' => $intent['amount']/100,
                    'currency' => $intent['currency'],
                    'description' => $data['display_items'][0]['custom']['description']
                ];
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function CheckIsTask()
    {

    }

    public function CheckIsUser()
    {

    }

    public function cancelled()
    {

    }
}