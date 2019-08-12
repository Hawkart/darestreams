<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Jobs\DeployJob;
use Carbon\Carbon;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        $job = (new DeployJob($request))->delay(Carbon::now()->addSeconds(3));
        dispatch($job);

        echo "deployed";
    }
}
