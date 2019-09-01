<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\GameRequest;
use App\Models\Notification;
use App\Notifications\NewGameOffer;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DB;
use Cache;

/**
 * @group Games
 */
class GameController extends Controller
{
    /**
     * GameController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['offer']);
    }

    /**
     * Display a listing of the resource.
     *
     * @queryParam include string String of connections: streams,tags, channels. Example: tags,streams
     * @queryParam sort string Sort items by fields: title, popularity. For desc use '-' prefix. Example: -popularity
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $games = QueryBuilder::for(Game::class)
            ->defaultSort('-popularity')
            ->allowedSorts('title', 'popularity')
            ->allowedIncludes(['streams', 'tags', 'channels'])
            ->jsonPaginate();

        return GameResource::collection($games);
    }

    /**
     * Display the specified resource.
     *
     * @queryParam include string String of connections: streams,tags,channels. Example: tags,streams
     *
     * @param  int  $game
     * @return \Illuminate\Http\Response
     */
    public function show($game)
    {
        $item = QueryBuilder::for(Game::class)
            ->allowedIncludes(['streams', 'tags', 'channels'])
            ->findOrFail($game);

        return new GameResource($item);
    }

    /**
     * Offer new category.
     * @authenticated
     *
     * @bodyParam title string required Title of new category. Example: New category.
     *
     * @param GameRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function offer(GameRequest $request)
    {
        if(Game::where('title', $request->get('title'))->exists())
            return setErrorAfterValidation(['title' => trans('api/game.failed_already_exists')]);

        Notification::route('mail', config('mail.game_offer_email'))
            ->notify(new NewGameOffer($request->get('title')));

        return response()->json([
            'success' => true,
            'message'=> trans('api/game.offer_success_created')
        ], 200);
    }

    /**
     * Top categories
     *
     * @queryParam hours integer. Check amount donations sum for last N hours. Default: 240. Example: 240
     * @queryParam limit integer. Limit of top categories. Default: 10.
     * @queryParam skip Integer. Offset of top categories. Default: 0.
     * @queryParam include string String of connections: streams,tags, channels. Example: tags,streams
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function top(Request $request)
    {
        $hours = $request->has('hours') ? $request->get('hours') : 240;
        $limit = $request->has('limit') ? $request->get('limit') : 8;
        $skip = $request->has('skip') ? $request->get('skip') : 0;
        $lastDays = Carbon::now()->subHours($hours);

        //Calculate cache key
        $queryParams = request()->query();
        ksort($queryParams);
        $queryString = http_build_query($queryParams);

        $cache_key = Str::slug('topCategories'.$queryString);
        $cacheTags = Cache::tags(['index', 'topCategories']);

        if ($cacheTags->get($cache_key)){
            $items = $cacheTags->get($cache_key);
        } else {

            //get streams finished amount donations for last 10 days
            $sub = DB::table('streams')->select('ch.id', 'ch.game_id', DB::raw("sum(amount_donations) as donates"))
                ->leftJoin('channels as ch', 'ch.id', '=', 'streams.channel_id')
                ->whereDate('start_at', '>=', DB::raw($lastDays->toDateString()))
                //->where('status', DB::raw(Stream::STATUS_FINISHED))
                ->groupBy('ch.id', 'ch.game_id')
                ->orderByDesc('donates');

            $list = DB::table( DB::raw("({$sub->toSql()}) as t") )
                ->mergeBindings($sub)
                ->select('t.*')
                ->whereNotNull('t.id')
                //->where('donates', '>', 0)
                ->pluck('game_id')
                ->toArray();

            $ids_ordered = implode(',', $list);
                $items = QueryBuilder::for(Game::class)
                ->whereIn('id', $list)
                ->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))
                ->allowedIncludes(['streams', 'tags', 'channels'])
                ->offset($skip)
                ->limit($limit)
                ->jsonPaginate();

            $cacheTags->put($cache_key, $items, 30);
        }

        return GameResource::collection($items);
    }
}
