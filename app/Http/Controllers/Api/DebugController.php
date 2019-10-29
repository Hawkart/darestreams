<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Jobs\DeployJob;
use Illuminate\Support\Facades\Log;

class DebugController extends Controller
{
    /**
     * @param Request $request
     */
    public function deploy(Request $request)
    {
        dispatch(new DeployJob($request->getContent(), $request->header('X-Hub-Signature')));

        echo "deployed";
    }

    /**
     * @param Request $request
     */
    public function logJs(Request $request)
    {
        Log::channel('daily_js')->debug('Js', $request->all());
    }
}