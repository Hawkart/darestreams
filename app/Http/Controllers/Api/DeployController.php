<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Jobs\DeployJob;

class DeployController extends Controller
{
    /**
     * @param Request $request
     */
    public function deploy(Request $request)
    {
        dispatch(new DeployJob($request->getContent(), $request->header('X-Hub-Signature')));

        echo "deployed";
    }
}
