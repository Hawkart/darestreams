<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AdvCampaignRequest;
use App\Models\AdvCampaign;
use App\Http\Resources\AdvCampaignResource;
use App\Rules\ValidCanUpdateCampaign;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Cache;
use File;

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
        $this->middleware('auth:api')->only(['store', 'update', 'index', 'show', 'updateLogo']);
    }

    /**
     * List of campaigns
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
     * Detail campaign.
     * @authenticated
     *
     * @return \Illuminate\Http\Response
     */
    public function show(AdvCampaign $campaign)
    {
        $user = auth()->user();

        if(!$user->isStreamer() && $campaign->user_id!=$user->id)
            return response()->json([], 401);

        $item = QueryBuilder::for($campaign->getQuery())
            ->allowedIncludes(['advTasks', 'tasks'])
            ->firstOrFail();

        return new AdvCampaignResource($item);
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

    /**
     * Update logo
     *
     * @authenticated
     * @bodyParam logo file required
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLogo(Request $request, AdvCampaign $campaign)
    {
        $request->validate([
            'logo' => [
                'required','image','mimes:jpeg,png,jpg,gif,svg','max:2048',
                new ValidCanUpdateCampaign($campaign),
            ]
        ]);

        if($campaign->logo)
        {
            $path = public_path() . '/storage/' . $campaign->logo;
            if(file_exists($path))
                unlink($path);
        }

        $logo = $campaign->id.'_logo'.time().'.'.request()->logo->getClientOriginalExtension();
        $request->logo->storeAs('public/campaigns', $logo);

        $campaign->logo = "campaigns/".$logo;
        $campaign->save();

        AdvCampaignResource::withoutWrapping();

        return response()->json([
            'success' => true,
            'data' => new AdvCampaignResource($campaign),
            'message'=> trans('api/campaign.success_updated')
        ], 200);
    }
}