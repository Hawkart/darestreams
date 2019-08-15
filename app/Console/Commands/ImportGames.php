<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Image;

class ImportGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import games from twitch.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws \TwitchApi\Exceptions\ClientIdRequiredException
     * @throws \TwitchApi\Exceptions\InvalidLimitException
     * @throws \TwitchApi\Exceptions\InvalidOffsetException
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(100);

        $twitchClient = new \TwitchApi\TwitchApi([
            'client_id' => config('app.twitch_api_cid')
        ]);

        $count = 0;
        $limit = 40;
        $offset = 0;

        do{
            $responseTwitch = $twitchClient->getTopGames((int)$limit, (int)$offset);

            if(!isset($responseTwitch['_total'])) break;

            $total = intval($responseTwitch['_total']);
            $games = $responseTwitch["top"];

            foreach($games as $arGame)
            {
                $item = Game::firstOrNew(['twitch_id' => $arGame["game"]["_id"]]);

                if(!isset($item->title))
                {
                    $item->title =  $arGame["game"]["name"];
                    $item->title_short =  $arGame["game"]["name"];
                    $item->twitch_id =  $arGame["game"]["_id"];
                    $item->logo = $this->getImagePath($arGame['game']['box']['large']);
                    $item->logo_small =  $this->getImagePath($arGame['game']['box']['small']);
                }

                $item->popularity =  $arGame["game"]["popularity"];
                $item->save();
            }

            $count+= count($games);
            $offset+= $limit;

            break;

        } while ($count<$total);

        $bar->finish();
    }

    /**
     * @param $path
     * @return string
     */
    public function getImagePath($path)
    {
        $filename = basename($path);
        Image::make($path)->save(public_path('storage/games/' . $filename));

        return 'games/' . $filename;
    }
}
