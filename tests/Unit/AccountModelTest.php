<?php

namespace Tests\Unit;

use App\Events\UserCreatedEvent;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class AccountModelTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function account_has_only_one_user()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $account = factory(Account::class)->create();

        $account->user()->associate($user);
        $account->save();

        Event::assertDispatched(UserCreatedEvent::class, function ($e) use ($user) {
            return $e->user->id === $user->id;
        });

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'user_id' => $user->id
        ]);
        $this->assertEquals($account->user->id, $user->id);
    }

    /** @test */
    public function account_has_many_transactions()
    {
        Event::fake();

        $users = factory(User::class, 2)->create();
        $account = factory(Account::class)->create(['user_id' => $users[0]->id]);
        $account2 = factory(Account::class)->create(['user_id' => $users[1]->id]);

        factory(Transaction::class, 5)->create([
            'account_sender_id' => $account->id,
            'account_receiver_id' => $account2->id,
            'task_id' => 0
        ]);
        factory(Transaction::class, 2)->create([
            'account_sender_id' => $account2->id,
            'account_receiver_id' => $account->id,
            'task_id' => 0
        ]);

        $tSentCount = $account->transactionsSent()->count();
        $tReceivedCount = $account->transactionsReceived()->count();
        $tAllCount = $account->getTransactions()->count();

        $this->assertEquals($tSentCount, 5);
        $this->assertEquals($tReceivedCount, 2);
        $this->assertEquals($tAllCount, 7);
    }

    /** @test */
    public function account_can_reset_amount_zero()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $account = factory(Account::class)->create([
            'user_id' => $user->id,
            'amount' => 200
        ]);

        $account->reset();

        $this->assertEquals($account->amount, 0);
    }
}