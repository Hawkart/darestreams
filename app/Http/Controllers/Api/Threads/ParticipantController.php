<?php

namespace App\Http\Controllers\Api\Threads;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Thread;
use App\Models\Message;
use App\Models\User;

/**
 * @group Threads participants
 */
class ParticipantController extends Controller
{
    /**
     * Get participant users of the chat.
     *
     * @param Request $request
     * @param Thread $thread
     */
    public function index(Request $request, Thread $thread)
    {
        $ids = $thread->participantsUserIds();
        $items = QueryBuilder::for(User::class)
            ->whereIn('id', $ids)
            ->jsonPaginate();

        return UserResource::collection($items);
    }
}
