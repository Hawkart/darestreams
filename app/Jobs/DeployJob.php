<?php

namespace App\Jobs;

use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class DeployJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @var
     */
    protected $request;

    /**
     * DeployJob constructor.
     * @param $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $githubPayload = $this->request->getContent();
        $githubHash = $this->request->header('X-Hub-Signature');
        $localToken = config('app.deploy_secret');
        $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);

        if (hash_equals($githubHash, $localHash))
        {
            $root_path = base_path();
            $process = new Process('cd ' . $root_path . '; ../deploy.sh');
            $process->run(function ($type, $buffer)
            {
                Log::info('Deploy info', [
                    'buffer' => $buffer,
                    'file' => __FILE__,
                    'line' => __LINE__
                ]);
            });
        }
    }
}
