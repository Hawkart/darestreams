<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

# Imports the Google Cloud client library
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;


class SpeechRecognize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'speech:recognize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $bar = $this->output->createProgressBar(100);

        //ffmpeg -i stereo.flac -ac 1 mono.flac
        //export GOOGLE_APPLICATION_CREDENTIALS=...

        $audioFile = __DIR__ . '/test/data/mono_2.flac';
        $content = file_get_contents($audioFile);

        $audio = (new RecognitionAudio())
            ->setContent($content);

        $config = new RecognitionConfig([
            'encoding' => AudioEncoding::FLAC,
            'sample_rate_hertz' => 16000,
            'language_code' => 'ru-RU'
        ]);

        $client = new SpeechClient();

        $response = $client->recognize($config, $audio);

        foreach ($response->getResults() as $result) {
            $alternatives = $result->getAlternatives();
            $mostLikely = $alternatives[0];
            $transcript = $mostLikely->getTranscript();
            printf('Transcript: %s' . PHP_EOL, $transcript);
        }

        $client->close();

        $bar->finish();
    }
}
