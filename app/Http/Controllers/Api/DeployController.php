<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        $githubPayload = $request->getContent();
        $githubHash = $request->header('X-Hub-Signature');
        $localToken = config('app.deploy_secret');
        $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);

        Log::info('Deploy info', [
            'gitHash' => $githubHash,
            'localToken' => $localToken,
            'localHash' => $localHash,
            'isEqual' => boolval(hash_equals($githubHash, $localHash)),
            'file' => __FILE__,
            'line' => __LINE__
        ]);

        if (hash_equals($githubHash, $localHash))
        {
            $root_path = base_path();
            $process = new Process('cd ' . $root_path . '; ../deploy.sh');
            $process->run(function ($type, $buffer) {
                echo $buffer;
            });
        }
    }
}
