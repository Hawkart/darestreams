<?php

namespace App\Http\Controllers\Api\AdvCampaigns;

use App\Enums\AdvTaskType;
use App\Http\Requests\AdvCampaignRequest;
use App\Http\Requests\AdvTaskRequest;
use App\Http\Resources\AdvTaskResource;
use App\Models\AdvCampaign;
use App\Models\AdvTask;
use App\Models\Rating\Channel as RatingChannel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Controllers\Api\Controller;

/**
 * @group Adv
 */
class AdvTaskController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'advertiser'])->only(['store', 'update', 'index', 'show']);
        $this->middleware(['auth:api', 'streamer'])->only(['all']);
    }

    /**
     * List of adv tasks for current streamer.
     * @authenticated
     *
     * @queryParam include string String of connections: campaign. Example: campaign
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function all(Request $request)
    {
        $user = auth()->user();

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
     * List of adv tasks for campaign.
     * @authenticated
     *
     * @queryParam include string String of connections: campaign. Example: campaign
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, AdvCampaign $campaign)
    {
        $user = auth()->user();

        if($campaign->user_id!=$user->id && @$user->isAdmin())
            return response()->json([], 401);

        $items = QueryBuilder::for(AdvTask::class)
            ->where('campaign_id', $campaign->id)
            ->allowedIncludes(['campaign'])
            ->jsonPaginate();

        return AdvTaskResource::collection($items);
    }

    /**
     * Detail task of campaign
     * @authenticated
     *
     * @queryParam include string String of connections: campaign, tasks, tasks.stream. Example: campaign
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, AdvCampaign $campaign, AdvTask $task)
    {
        $user = auth()->user();

        if($campaign->user_id!=$user->id && @$user->isAdmin())
            return response()->json([], 401);

        $item = QueryBuilder::for(AdvTask::class)
            ->allowedIncludes(['campaign', 'tasks', 'tasks.stream'])
            ->findOrFail($task->id);

        AdvTaskResource::withoutWrapping();

        return new AdvTaskResource($item);
    }

    /**
     * Create new campaign's task.
     * @authenticated
     *
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
    public function store(AdvTaskRequest $request, AdvCampaign $campaign)
    {
        $input = $request->all();
        $input['campaign_id'] = $campaign->id;

        $task = new AdvTask();
        $task->fill($input);
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
    public function update(AdvTaskRequest $request, AdvCampaign $campaign, AdvTask $task)
    {
        $task->update($request->only(['small_desc', 'full_desc', 'limit', 'price', 'type', 'min_rating']));

        AdvTaskResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new AdvTaskResource($task),
            'message'=> trans('api/adv_task.success_updated')
        ], 200);
    }


    /**
     * Get list of types
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function types()
    {
        return response()->json(AdvTaskType::getInstances(), 200);
    }
}