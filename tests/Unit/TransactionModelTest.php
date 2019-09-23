<?php

namespace Tests\Unit;

use App\Events\TransactionCreatedEvent;
use App\Events\TransactionUpdatedEvent;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Game;
use App\Models\Stream;
use App\Models\Task;
use App\Models\Transaction;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class TransactionModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function check_listener_on_create()
    {
        Event::fake();

        $users = factory(User::class, 2)->create();
        $account = factory(Account::class)->create(['user_id' => $users[0]->id, 'amount' => 1000]);
        $account2 = factory(Account::class)->create(['user_id' => $users[1]->id, 'amount' => 1000]);

        $transaction = factory(Transaction::class)->create(['task_id' => 0]);

        Event::assertDispatched(TransactionCreatedEvent::class, function ($e) use ($transaction) {
            return $e->transaction->id === $transaction->id;
        });
    }

    /** @test */
    public function check_listener_on_update()
    {
        Event::fake();

        $users = factory(User::class, 2)->create();
        $account = factory(Account::class)->create(['user_id' => $users[0]->id, 'amount' => 1000]);
        $account2 = factory(Account::class)->create(['user_id' => $users[1]->id, 'amount' => 1000]);

        $transaction = factory(Transaction::class)->create(['task_id' => 0, 'amount' => 10]);
        $transaction->update(['amount' => 20]);

        Event::assertDispatched(TransactionUpdatedEvent::class, function ($e) use ($transaction) {
            return $e->transaction->id === $transaction->id;
        });
    }

    /** @test */
    public function it_belongs_to_task()
    {
        $user = factory(User::class)->create();
        factory(Game::class)->create();
        factory(Channel::class)->create();
        factory(Stream::class)->create();

        $task = factory(Task::class)->create();

        $transaction = factory(Transaction::class)->create(['account_sender_id' => $user->account->id]);
        $transaction->task()->associate($task);
        $transaction->save();

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'task_id' => $task->id
        ]);

        $this->assertEquals($transaction->task->id, $task->id);
    }

    /** @test */
    public function it_belongs_to_sender_or_receiver_account()
    {
        $users = factory(User::class, 2)->create();
        $account =  $users[0]->account;
        $account2 = $users[1]->account;

        $account->update(['amount' => 200]);
        $account2->update(['amount' => 200]);

        $transaction = factory(Transaction::class)->create(['task_id' => 0, 'amount' => 10]);

        $transaction->accountSender()->associate($account);
        $transaction->accountReceiver()->associate($account2);
        $transaction->save();

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'account_sender_id' => $account->id
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'account_receiver_id' => $account2->id
        ]);

        $this->assertEquals($transaction->accountSender->id, $account->id);
        $this->assertEquals($transaction->accountReceiver->id, $account2->id);
    }
}