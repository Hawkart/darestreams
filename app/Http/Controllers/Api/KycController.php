<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\KycRequest;
use App\Http\Requests\PhoneRequest;
use App\Models\Kyc;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

/**
 * @group Kycs
 */
class KycController extends Controller
{
    /**
     * KycController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'verifyNumber', 'sendSmsCode']);
    }

    /**
     * Create new kyc.
     *
     * @authenticated
     *
     * @param KycRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(KycRequest $request)
    {
        $user = auth()->user();

        if(!$user->kyc)
        {
            $kyc = new Kyc();
        }else{
            $kyc = $user->kyc;

            if($kyc->personal_verified)
                abort(response()->json(['message' => 'Personal data has already verified'], 403));
        }

        $kyc->fill($request->all());
        $kyc->save();

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * Send sms for verification
     *
     * @authenticated
     *
     * @param PhoneRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Twilio\Exceptions\ConfigurationException
     * @throws \Twilio\Exceptions\TwilioException
     */
    public function sendSmsCode(PhoneRequest $request)
    {
        $user = auth()->user();
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');
        $client = new Client($sid, $token);

        $code = rand(100000, 999999);

        $result = $client->messages->create(
            $request->get('phone'),
            [
                'from' => $from,
                'body' => $code
            ]
        );

        if($result)
            $user->update(["sms_code" => $code]);

        return response()->json([
            'success' => true
        ], 200);
    }

    /**
     * Verify KYC
     *
     * @authenticated
     * @param $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify($code)
    {
        $user = auth()->user();

        //Todo: Validate request
        if($user->sms_code != $code)
            abort(response()->json(['message' => 'Code is not verified'], 403));

        $user->kyc->update(['personal_verified' => true]);
        $user->update(['sms_code' => null]);

        return response()->json([
            'success' => true
        ], 200);
    }
}