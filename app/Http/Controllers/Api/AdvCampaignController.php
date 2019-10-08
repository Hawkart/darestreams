<?php

namespace App\Http\Controllers\Api;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Http\Resources\ThreadResource;
use App\Models\Channel;
use Carbon\Carbon;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Http\Request;
use App\Models\Stream;
use App\Http\Resources\StreamResource;
use App\Http\Requests\StreamRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
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
        $this->middleware('auth:api')->only(['store', 'update']);
    }
}