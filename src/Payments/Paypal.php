<?php

namespace Dare\Payments;

use Dare\Contracts\PaymentInterface;
use Illuminate\Http\Request;
use Omnipay\Omnipay;

class Paypal implements PaymentInterface
{
    public $gate;

    public function init()
    {
        $gateway = Omnipay::create('PayPal_Rest');

        $gateway->initialize(array(
            'clientId' => config('services.paypal.client_id'),
            'secret'   => config('services.paypal.secret'),
            'testMode' => config('services.paypal.sandbox')
        ));

        $this->gate = $gateway;
    }

    public function checkout(array $params)
    {
        $user_id = $params['user_id'];
        $task_id = $params['task_id'];

        $data = [
            'returnUrl'=> route('payment.checkout.completed', ['gateway' => 'paypal', 'user' => $user_id, 'task' => $task_id]),
            'cancelUrl' => route('payment.checkout.cancelled', ['gateway' => 'paypal', 'user' => $user_id, 'task' => $task_id]),
            'amount'        => $params['amount'],
            'currency'      => $params['currency'],
            'transactionId' => $params['transactionId'],
            'description'   => $params['description'],
        ];

        $transaction = $this->gate->authorize($data);
        $response = $transaction->send();

        if ($response->isRedirect())
        {
            $data = $response->getData();

            $params['result']->update(['exid' => $data['id']]);

            $redirectUrl = $response->getRedirectUrl();
            return redirect()->to($redirectUrl);
        }
    }

    public function completed(Request $request)
    {
        $transaction = $this->gate->completePurchase(array(
            'payerId'             => $request->get('PayerID'),
            'transactionReference' => $request->get('paymentId'),
        ));

        $result = $transaction->send();

        if ($data = $result->getData())
        {
            $transaction = $data['transactions'][0];

            return [
                'status' => (isset($data['state']) && $data['state'] == 'approved') ? "completed" : "",
                'data' => $data,
                'order_id' => $transaction['invoice_number'],
                'transaction_id' => $transaction['id'],
                'amount' => $transaction['amount']['total'],
                'currency' => $transaction['amount']['currency'],
                'description' => $transaction['description']
            ];
        } else {
            return [];
        }
    }
}