<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AdvCampaignRequest;
use App\Http\Requests\AdvTaskRequest;
use App\Http\Resources\AdvTaskResource;
use App\Models\AdvTask;
use App\Models\Rating\Channel as RatingChannel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Adv
 */
class AdvTaskController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'update', 'index']);
    }

    /**
     * List of adv tasks for current user.
     * @authenticated
     *
     * @queryParam include string String of connections: campaign. Example: campaign
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if(!$user->isStreamer() && !$user->isAdmin())
            return response()->json([], 401);

        $rh = RatingChannel::where('channel_id', $user->channel->id)->first();
        $rating = $rh ? ceil($rh->rating/1000) : 0;

        $items = QueryBuilder::for(AdvTask::class)
            ->whereHas('campaign', function($q){
                $q->active();
            })
            ->where('min_rating', '<', $rating)
            ->allowedIncludes(['campaign'])
            ->jsonPaginate();

        return AdvTaskResource::collection($items);
    }

    /**
     * Create new campaign's task.
     * @authenticated
     *
     * @bodyParam campaign_id integer required Int of campaign.
     * @bodyParam small_desc string required Small description.
     * @bodyParam full_desc string required Full description.
     * @bodyParam limit integer required
     * @bodyParam price integer required Brand of campaign.
     * @bodyParam type integer required Type of limit. 0 - by stream, 1- by views.
     * @bodyParam min_rating integer required Min rating of streamer.
     *
     * @param AdvCampaignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdvTaskRequest $request)
    {
        $task = new AdvTask();
        $task->fill($request->all());
        $task->save();

        AdvTaskResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new AdvTaskResource($task),
            'message'=> trans('api/adv_task.success_created')
        ], 200);
    }

    /**
     * Update campaign's task.
     * @authenticated
     *
     * @bodyParam small_desc string Small description.
     * @bodyParam full_desc string Full description.
     * @bodyParam limit integer
     * @bodyParam price integer required Brand of campaign.
     * @bodyParam type integer Type of limit. 0 - by stream, 1- by views.
     * @bodyParam min_rating integer Min rating of streamer.
     */
    public function update(AdvTaskRequest $request, AdvTask $task)
    {
        $task->update($request->only(['small_desc', 'full_desc', 'limit', 'price', 'type', 'min_rating']));

        AdvTaskResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new AdvTaskResource($task),
            'message'=> trans('api/adv_task.success_updated')
        ], 200);
    }
}