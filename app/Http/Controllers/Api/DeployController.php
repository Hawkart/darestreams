<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Jobs\DeployJob;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        dispatch(new DeployJob($request));

        echo "deployed";
    }
}
