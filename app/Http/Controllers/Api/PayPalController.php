<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\DB;

/**
 * Class PayPalController
 * @package App\Http\Controllers
 */
class PayPalController extends Controller
{
    private $gateway;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $gateway = Omnipay::create('PayPal_Rest');

        // Initialise the gateway
        $gateway->initialize(array(
            'clientId' => config('services.paypal.client_id'),
            'secret'   => config('services.paypal.secret'),
            'testMode' => config('services.paypal.sandbox')
        ));

        $this->gateway = $gateway;
    }

    /**
     * Create a payment.
     *
     * @bodyParam user_id integer required User's id.
     * @bodyParam amount float required Amount for payment.
     * @bodyParam task_id integer The task the donation going to.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);

        $userReceiver = User::findOrFail($request->get('user_id'));

        $data = [
            'account_receiver_id' => $userReceiver->account->id,
            'amount' => $request->get('amount'),
            'task_id' => $request->has('task_id') ? $request->get('task_id') : 0,
            'comment' => $request->has('task_id') ? "Donate to the task" : "Money transfer from PayPal."
        ];

        if(auth()->user())
            $data['account_sender_id'] = auth()->user()->account->id;

        try {
            $result = DB::transaction(function () use ($data) {
                return Transaction::create($data);
            });
        } catch (\Exception $e) {
            return response($e->getMessage(), 422);
        }

        $transaction = $this->gateway->authorize(array(
            'amount'        => $result->amount,
            'currency'      => 'RUB',
            'transactionId' => $result->id,
            'description'   => auth()->user() ? 'Transaction from '.auth()->user()->name : 'Transaction from unauthorized user.',
            'returnUrl'=> action('Api\PayPalController@completed'),
            'cancelUrl' => action('Api\PayPalController@cancelled'),
        ));

        $response = $transaction->send();

        if ($response->isRedirect())
        {
            $redirectUrl = $response->getRedirectUrl();
            return redirect()->to($redirectUrl);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function completed(Request $request)
    {
        $transaction = $this->gateway->completePurchase(array(
            'payerId'             => $request->get('PayerID'),
            'transactionReference' => $request->get('paymentId'),
        ));

        $result = $transaction->send();

        if ($data = $result->getData())
        {
            $transaction = $data['transactions'][0];

            if ($data['state'] == 'approved')
            {
                $t = Transaction::findOrFail($transaction['invoice_number']);

                $amount = $transaction['amount']['total'];
                $currency = $transaction['amount']['currency'];

                if ($t->currency != $currency)
                    return response()->json(['error' => trans('Currency is not match.')], 422);

                if ($t->status != Transaction::PAYMENT_COMPLETED)
                {
                    try {
                        $t = DB::transaction(function () use ($data, $t, $amount) {
                            $t->update([
                                'status' => Transaction::PAYMENT_COMPLETED,
                                'amount' => $amount,
                                //'transaction_id' => $result->getTransactionReference();
                                'json' => $data
                            ]);

                            //Todo: Update balance or in listener todo
                            //$t->accountReceiver->update(['amount' => ])
                            return $t;
                        });
                    }catch (\Exception $e) {
                        return response($e->getMessage(), 422);
                    }
                }

                return new TransactionResource($t);
            }else{
                return response()->json(['error' => trans('Payment is not approved.')], 422);
            }
        }else{
            return response()->json(['error' => trans('Complete Purchase request mistake.')], 422);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelled(Request $request)
    {
        return response()->json(['error' => trans('You have cancelled your recent PayPal payment!')], 422);
    }
}
