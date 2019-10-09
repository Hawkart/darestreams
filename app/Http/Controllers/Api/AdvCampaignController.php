<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AdvCampaignRequest;
use App\Models\AdvCampaign;
use App\Http\Resources\AdvCampaignResource;
use Cache;

/**
 * @group Adv
 */
class AdvCampaignController extends Controller
{
    /**
     * AdvCampaignController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'update', 'index']);
    }

    /**
     * Display a listing of the resource.
     * @authenticated
     *
     * @queryParam include string String of connections: advTasks, tasks. Example: advTasks
     * @queryParam page array Use as page[number]=1&page[size]=2.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!auth()->user()->isStreamer())
            return response()->json([], 401);

        $items = QueryBuilder::for(AdvCampaign::class)
            ->active()
            ->allowedIncludes(['advTasks', 'tasks'])
            ->jsonPaginate();

        return AdvCampaignResource::collection($items);
    }

    /**
     * Create new campaign.
     * @authenticated
     *
     * @bodyParam from datetime required Period date from.
     * @bodyParam to datetime required Period date to.
     * @bodyParam title string required Title of campaign.
     * @bodyParam brand string required Brand of campaign.
     * @bodyParam limit integer required Limit to spend on all tasks.
     *
     * @param AdvCampaignRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AdvCampaignRequest $request)
    {
        $user = auth()->user();

        $input = $request->all();
        $input['user_id'] = $user->id;

        $campaign = new AdvCampaign();
        $campaign->fill($input);
        $campaign->save();

        AdvCampaignResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new AdvCampaignResource($campaign),
            'message'=> trans('api/campaign.success_created')
        ], 200);
    }

    /**
     * Update campaign.
     * @authenticated
     *
     * @bodyParam from datetime Period date from.
     * @bodyParam to datetime Period date to.
     * @bodyParam title string required Title of campaign.
     * @bodyParam brand string Brand of campaign.
     * @bodyParam limit integer Limit to spend on all tasks.
     */
    public function update(AdvCampaignRequest $request, AdvCampaign $campaign)
    {
        $campaign->update($request->only(['from', 'to', 'title', 'brand', 'limit']));

        AdvCampaignResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new AdvCampaignResource($campaign),
            'message'=> trans('api/campaign.success_updated')
        ], 200);
    }
}