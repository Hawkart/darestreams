<?php

namespace Tests\Feature;

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\VoteStatus;
use App\Http\Resources\TaskResource;
use App\Models\AdvCampaign;
use App\Models\AdvTask;
use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function not_auth_user_cannot_create_it()
    {
        factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        $stream = factory(Stream::class)->create();
        $task = factory(Task::class)->make();

        $this->json('POST', '/api/tasks', $task->toArray())
            ->assertStatus(401);
    }

    /** @test */
    public function auth_user_create_it_but_stream_dont_exist()
    {
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 500]);
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create();

        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'game_id' => $game->id,
            'title' => "First stream",
            'link' => "https://www.twitch.tv/darestreams_",
            'start_at' => Carbon::now('UTC')->addMinutes(15),
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 5,
            'min_amount_donate_task_before_stream' => 5
        ]);

        $data = factory(Task::class)->make([
            'stream_id' => 200
        ])->toArray();

        $this->storeAssertFieldFailed($data, $token, 'stream_id');

        unset($data['stream_id']);
        $this->storeAssertFieldFailed($data, $token, 'stream_id');
    }

    /** @test */
    public function auth_user_create_it_but_stream_statuses_failed()
    {
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 500]);
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create();

        //check stream already finished
        $finishedStatuses = [StreamStatus::FinishedWaitPay, StreamStatus::FinishedIsPayed];
        foreach($finishedStatuses as $status)
        {
            $stream = factory(Stream::class)->create(['status' => $status]);
            $data = factory(Task::class)->make(['stream_id' => $stream->id])->toArray();
            $this->storeAssertFieldFailed($data, $token, 'created_amount');
        }

        //check can create before or while stream
        $stream = factory(Stream::class)->create(['status' => StreamStatus::Created, 'allow_task_before_stream' => 0]);
        $data = factory(Task::class)->make(['stream_id' => $stream->id])->toArray();
        $this->storeAssertFieldFailed($data, $token, 'created_amount');

        $stream = factory(Stream::class)->create(['status' => StreamStatus::Active, 'allow_task_when_stream' => 0]);
        $data = factory(Task::class)->make(['stream_id' => $stream->id])->toArray();
        $this->storeAssertFieldFailed($data, $token, 'created_amount');
    }

    /** @test */
    public function auth_user_not_owner_create_it_but_create_amount_wrong()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 10]);
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $owner->id]);

        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'status' => StreamStatus::Created,
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 20
        ]);

        //check created_amount integer >=0 and required
        $data = factory(Task::class)->make(['stream_id' => $stream->id, 'created_amount' => -1])->toArray();
        $this->storeAssertFieldFailed($data, $token, 'created_amount');

        unset($data['created_amount']);
        $this->storeAssertFieldFailed($data, $token, 'created_amount');

        //check created_amount for task more than min amount in stream
        $data = factory(Task::class)->make(['stream_id' => $stream->id, 'created_amount' => 10])->toArray();
        $this->storeAssertFieldFailed($data, $token, 'created_amount');

        //check user don't have enough money
        $data = factory(Task::class)->make(['stream_id' => $stream->id, 'created_amount' => 20])->toArray();
        $this->storeAssertFieldFailed($data, $token, 'created_amount', 402, false);
    }

    /** @test */
    public function auth_user_create_but_requires_fields_not_filled()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 50]);
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $owner->id]);

        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'status' => StreamStatus::Created,
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 10
        ]);

        $fields = ['small_desc', 'full_desc'];

        foreach($fields as $field)
        {
            $data = factory(Task::class)->make(['stream_id' => $stream->id, 'created_amount' => 10])->toArray();

            $data[$field] = '';
            $this->storeAssertFieldFailed($data, $token, $field);

            unset($data[$field]);
            $this->storeAssertFieldFailed($data, $token, $field);
        }
    }

    /** @test */
    public function auth_user_create_successfully()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 50]);
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $owner->id]);

        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'status' => StreamStatus::Created,
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 10
        ]);

        $data = factory(Task::class)->make([
            'stream_id' => $stream->id,
            'created_amount' => 10,
            'small_desc' => 'First task'
        ])->toArray();

        $this->json('POST', '/api/tasks', $data, ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('tasks', [
            'stream_id' => $stream->id,
            'created_amount' => 10,
            'small_desc' => 'First task'
        ]);

        $task = Task::where('stream_id', $stream->id)->first();

        $this->assertDatabaseHas('transactions', [
            'task_id' => $task->id,
            'amount' => 10,
            'account_sender_id' => $user->account->id,
            'account_receiver_id' => $owner->account->id,
        ]);
    }

    /** @test */
    public function auth_user_create_with_adv_task_id_not_exist()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 50]);
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $owner->id]);

        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'status' => StreamStatus::Created,
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 10
        ]);

        $data = [
            'stream_id' => $stream->id,
            'adv_task_id' => 5
        ];

        $this->storeAssertFieldFailed($data, $token, 'adv_task_id', 404, false);
    }

    /** @test */
    public function auth_user_create_with_avt_task_id_successfully()
    {
        $owner = factory(User::class)->create(['role_id' => 3]);
        $owner->account->update(['amount' => 500]);
        $token = auth()->login($owner);
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $owner->id]);
        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'status' => StreamStatus::Created,
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 10
        ]);

        $advertiser = factory(User::class)->create(['role_id' => 4]);

        $campaign = factory(AdvCampaign::class)->create([
            'user_id' => $advertiser->id,
            'from' => Carbon::now('UTC')->subMinutes(15)->toDateTimeString(),
            'to' => Carbon::now('UTC')->addMinutes(245)->toDateTimeString()
        ]);

        $advTask = factory(AdvTask::class)->create([
            'campaign_id' => $campaign->id,
            'small_desc' => 'task',
            'full_desc' => 'full task',
            'limit' => 50,
            'type' => 1,
            'price' => 5,
            'min_rating' => 0
        ]);

        $data = [
            'stream_id' => $stream->id,
            'adv_task_id' => $advTask->id
        ];

        $this->json('POST', '/api/tasks', $data, ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('tasks', $data);
    }

    /** @test */
    public function show_list_of_this()
    {
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 5000]);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'status' => StreamStatus::Created,
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 10
        ]);

        $tasks = factory(Task::class, 5)->create([
            'stream_id' => $stream->id,
            'created_amount' => 10
        ]);

        $this->assertDatabaseHas('tasks', [
            'stream_id' => $stream->id,
        ]);

        $this->json('GET', '/api/tasks?stream_id='.$stream->id, [], [])
            ->assertStatus(200);
            //->assertJsonCount(5, 'data');
    }

    /** @test */
    public function show_detail()
    {
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 5000]);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $user->id]);

        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'status' => StreamStatus::Created,
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 10
        ]);

        $task = factory(Task::class)->create([
            'stream_id' => $stream->id,
            'created_amount' => 10
        ]);

        $task->refresh();
        $r = new TaskResource($task);
        $d = json_decode(json_encode($r->toResponse(app('request'))->getData()), true);
        unset($d['stream']);

        $this->json('get', '/api/tasks/'.$task->id)
            ->assertStatus(200)
            ->assertJsonFragment($d);
    }


    /** @test */
    public function auth_user_update_as_owner_for_stream_not_active_or_created_should_failed()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 50]);
        $owner->account->update(['amount' => 50]);
        $token = auth()->login($owner);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $owner->id]);

        $statuses = [StreamStatus::Canceled, StreamStatus::FinishedIsPayed, StreamStatus::FinishedWaitPay];

        foreach($statuses as $status)
        {
            $stream = factory(Stream::class)->create([
                'channel_id' => $channel->id,
                'status' => $status,
                'allow_task_before_stream' => 1,
                'min_amount_task_before_stream' => 10,
                'allow_task_when_stream' => 1,
                'min_amount_task_when_stream' => 10,
            ]);

            $task = factory(Task::class)->create([
                'user_id' => $user->id,
                'stream_id' => $stream->id,
                'created_amount' => 10,
                'small_desc' => 'First task',
            ]);

            $this->updateAssertFieldFailed('/api/tasks/'.$task->id, ['status' => TaskStatus::Active], $token, 'status');
        }
    }

    /** @test */
    public function auth_user_as_owner_update_for_task_in_status_more_than_create_should_failed()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 50]);
        $owner->account->update(['amount' => 50]);
        $token = auth()->login($user);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $owner->id]);

        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'status' => StreamStatus::Active,
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 10,
            'allow_task_when_stream' => 1,
            'min_amount_task_when_stream' => 10,
        ]);

        $task = factory(Task::class)->create([
            'user_id' => $user->id,
            'stream_id' => $stream->id,
            'created_amount' => 10,
            'small_desc' => 'First task',
            'status' => TaskStatus::Active,
        ]);

        $this->updateAssertFieldFailed('/api/tasks/'.$task->id, ['small_desc' => 'New desc'], $token, 'status');
    }

    /** @test */
    public function user_update_it_successfully()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $user->account->update(['amount' => 50]);
        $owner->account->update(['amount' => 50]);
        $token = auth()->login($owner);

        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $owner->id]);

        $stream = factory(Stream::class)->create([
            'channel_id' => $channel->id,
            'status' => StreamStatus::Active,
            'allow_task_before_stream' => 1,
            'min_amount_task_before_stream' => 10,
            'allow_task_when_stream' => 1,
            'min_amount_task_when_stream' => 10,
        ]);

        $task = factory(Task::class)->create([
            'user_id' => $user->id,
            'stream_id' => $stream->id,
            'created_amount' => 10,
            'small_desc' => 'First task',
            'status' => TaskStatus::Created,
        ]);

        $this->json('PUT', '/api/tasks/'.$task->id, ['status' => TaskStatus::Active],  ['Authorization' => "Bearer $token"])
            ->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
                'message'
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatus::Active
        ]);
    }

    /** @test */
    public function not_auth_user_try_to_vote()
    {
        $user = factory(User::class)->create();
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create();
        $stream = factory(Stream::class)->create();
        $task = factory(Task::class)->create();

        $this->json('PATCH', '/api/tasks/'.$task->id."/set-vote", [])
            ->assertStatus(401);
    }

    /** @test */
    public function auth_user_try_to_vote_but_didnt_donate_should_failed()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $userA->id]);
        $stream = factory(Stream::class)->create(['channel_id' => $channel->id]);
        $task = factory(Task::class)->create(['user_id' => $userA->id]);

        $token = auth()->login($userB);

        $this->json('PATCH', '/api/tasks/'.$task->id."/set-vote", [], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['vote'],
                'message'
            ]);
    }

    /** @test */
    public function auth_user_try_to_vote_but_already_did()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $userB->account->update(['amount' => 1000]);
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $userA->id]);
        $stream = factory(Stream::class)->create(['channel_id' => $channel->id]);
        $task = factory(Task::class)->create(['user_id' => $userA->id]);

        $transaction = Transaction::create([
            'task_id' => $task->id,
            'amount' => 10,
            'account_sender_id' => $userB->account->id,
            'account_receiver_id' => $userA->account->id,
            'status' => TransactionStatus::Holding,
            'type' => TransactionType::Donation
        ]);

        $token = auth()->login($userB);

        $vote = Vote::where('task_id', $task->id)->where('user_id', $userB->id)->first();
        $vote->update(['vote' => VoteStatus::Yes]);

        $this->json('PATCH', '/api/tasks/'.$task->id."/set-vote", ['vote' => VoteStatus::No], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['vote'],
                'message'
            ]);
    }

    /** @test */
    public function auth_user_try_to_vote_but_wrong_task_status()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $userB->account->update(['amount' => 1000]);
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $userA->id]);
        $stream = factory(Stream::class)->create(['channel_id' => $channel->id]);
        $task = factory(Task::class)->create(['user_id' => $userA->id, 'status' => TaskStatus::Active]);

        $transaction = Transaction::create([
            'task_id' => $task->id,
            'amount' => 10,
            'account_sender_id' => $userB->account->id,
            'account_receiver_id' => $userA->account->id,
            'status' => TransactionStatus::Holding,
            'type' => TransactionType::Donation
        ]);

        $token = auth()->login($userB);

        $this->json('PATCH', '/api/tasks/'.$task->id."/set-vote", ['vote' => VoteStatus::No], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['vote'],
                'message'
            ]);
    }

    /** @test */
    public function auth_user_votes_successfully()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $userB->account->update(['amount' => 1000]);
        $game = factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $userA->id]);
        $stream = factory(Stream::class)->create(['channel_id' => $channel->id]);
        $task = factory(Task::class)->create(['user_id' => $userA->id, 'status' => TaskStatus::AllowVote]);

        Transaction::create([
            'task_id' => $task->id,
            'amount' => 10,
            'account_sender_id' => $userB->account->id,
            'account_receiver_id' => $userA->account->id,
            'status' => TransactionStatus::Holding,
            'type' => TransactionType::Donation
        ]);

        $token = auth()->login($userB);

        $this->json('PATCH', '/api/tasks/'.$task->id."/set-vote", ['vote' => VoteStatus::No], ['Authorization' => "Bearer $token"])
            ->assertStatus(200);
    }

    /** @test */
    public function not_auth_user_try_donate()
    {
        $userA = factory(User::class)->create();
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $userA->id]);
        factory(Stream::class)->create(['channel_id' => $channel->id]);
        $task = factory(Task::class)->create(['user_id' => $userA->id, 'status' => TaskStatus::AllowVote]);

        $this->json('POST', '/api/tasks/'.$task->id."/donate", [])
            ->assertStatus(401);
    }

    /** @test */
    public function auth_user_try_donate_on_his_task_but_status_wrong()
    {
        $userA = factory(User::class)->create();
        $userA->account->update(['amount' => 500]);
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $userA->id]);
        factory(Stream::class)->create(['channel_id' => $channel->id]);
        $task = factory(Task::class)->create(['user_id' => $userA->id, 'status' => TaskStatus::AllowVote]);

        $token = auth()->login($userA);

        $this->json('POST', '/api/tasks/'.$task->id."/donate", ['amount' => 10], ['Authorization' => "Bearer $token"])
            ->assertStatus(422)
            ->assertJsonStructure([
                'errors' => ['amount'],
                'message'
            ]);
    }

    /** @test */
    public function auth_user_try_donate_on_his_task_but_not_enough_money()
    {
        $userA = factory(User::class)->create();
        $userA->account->update(['amount' => 10]);
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $userA->id]);
        factory(Stream::class)->create(['channel_id' => $channel->id, 'status' => StreamStatus::Active]);
        $task = factory(Task::class)->create(['user_id' => $userA->id, 'status' => TaskStatus::Created, 'min_donation' => 30]);

        $token = auth()->login($userA);

        $this->json('POST', '/api/tasks/'.$task->id."/donate", ['amount' => 50], ['Authorization' => "Bearer $token"])
            ->assertStatus(402);
    }

    /** @test */
    public function auth_user_try_donate_successfully()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();
        $userB->account->update(['amount' => 1000]);
        factory(Game::class)->create();
        $channel = factory(Channel::class)->create(['user_id' => $userA->id]);
        factory(Stream::class)->create(['channel_id' => $channel->id]);
        $task = factory(Task::class)->create([
            'user_id' => $userA->id,
            'status' => TaskStatus::Active,
            'min_donation' => 30
        ]);

        $token = auth()->login($userB);

        $this->json('POST', '/api/tasks/'.$task->id."/donate", ['amount' => 50], ['Authorization' => "Bearer $token"])
            ->assertStatus(200);

        $this->assertDatabaseHas('transactions', [
            'task_id' => $task->id,
            'amount' => 50,
            'account_sender_id' => $userB->account->id,
            'account_receiver_id' => $userA->account->id,
            'status' => TransactionStatus::Holding,
            'type' => TransactionType::Donation
        ]);
    }

    /**
     * @param $data
     * @param $token
     * @param $fields
     * @param int $status
     * @param bool $json_structure
     */
    public function storeAssertFieldFailed($data, $token, $fields, $status = 422, $json_structure = true)
    {
        $response = $this->json('POST', '/api/tasks', $data, ['Authorization' => "Bearer $token"])
            ->assertStatus($status);

        if($json_structure)
            $response->assertJsonStructure([
                'errors' => [$fields],
                'message'
            ]);
    }

    /**
     * @param $url
     * @param $data
     * @param $token
     * @param $fields
     * @param int $status
     */
    public function updateAssertFieldFailed($url, $data, $token, $fields, $status = 422, $json_structure = true)
    {
        $response = $this->json('PUT', $url, $data, ['Authorization' => "Bearer $token"])
            ->assertStatus($status);

        if($json_structure)
            $response->assertJsonStructure([
                 'errors' => [$fields],
                 'message'
            ]);
    }
}
