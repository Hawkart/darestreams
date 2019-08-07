<?php

namespace App\Http\Controllers\Api\Streams;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Http\Resources\MessageResource;
use App\Models\Stream;
use App\Models\Message;

/**
 * @group Streams messages
 */
class MessageController extends Controller
{
    /**
     * @param Request $request
     * @param Stream $stream
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, Stream $stream)
    {
        $query = $stream->threads[0]->messages()->getQuery();

        $items = QueryBuilder::for($query)
            ->allowedIncludes(['user', 'thread'])
            ->jsonPaginate();

        return MessageResource::collection($items);
    }

    /**
     * @param Stream $stream
     * @param Message $message
     */
    public function show(Stream $stream, Message $message)
    {
        if(!$stream->threads[0]->messages()->where('id', $message->id)->exists())
            return response()->json(['error' => trans('api/streams/tasks/transaction.task_not_belong_to_stream')], 403);

        $item = QueryBuilder::for(Message::whereId($message->id))
            ->allowedIncludes(['user', 'thread'])
            ->first();

        return new MessageResource($item);
    }

    /**
     *
     */
    public function store()
    {

    }
}
