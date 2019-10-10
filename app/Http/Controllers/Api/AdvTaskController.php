<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AdvCampaignRequest;
use App\Http\Requests\AdvTaskRequest;
use App\Models\AdvTask;
use Illuminate\Http\Request;

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
    public function store(AdvTaskRequest $request)
    {

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
    public function update(AdvTaskRequest $request, AdvTask $task)
    {

    }
}