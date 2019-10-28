<?php

namespace App\Http\Controllers\Api;

use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TransactionResource;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StreamRequest;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * @group Transactions
 */
class TransactionController extends Controller
{
    /**
     * TransactionController constructor.
     */
    public function __construct()
    {
        //$this->middleware('auth:api')->only(['store']);
    }

    /**
     * Get list of statuses
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statuses()
    {
        return response()->json(TransactionStatus::getInstances(), 200);
    }

    /**
     * Get list of types
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function types()
    {
        return response()->json(TransactionType::getInstances(), 200);
    }
}
