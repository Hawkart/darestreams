<?php

namespace App\Http\Controllers\Api\Threads;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Models\Thread;
use App\Models\Message;

class ParticipantController extends Controller
{
    /**
     * @param Request $request
     * @param Thread $thread
     */
    public function index(Request $request, Thread $thread)
    {

    }

    /**
     * @param Thread $thread
     * @param Message $message
     */
    public function show(Thread $thread, Message $message)
    {

    }

    /**
     * @param Request $request
     * @param Thread $thread
     */
    public function store(Request $request, Thread $thread)
    {

    }

    /**
     * @param Request $request
     * @param Thread $thread
     * @param Message $message
     */
    public function update(Request $request, Thread $thread, Message $message)
    {

    }
}
