<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Task;
use App\Models\Account;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i<=1000; $i++)
        {
            $task = Task::inRandomOrder()->first();
            $receiver = $task->stream->user->account;
            $sender = Account::where('id', '<>', $receiver->id)->inRandomOrder()->first();

            factory(Transaction::class, 1000)->create([
                'task_id' => $task->id,
                'account_receiver_id' => $receiver->id,
                'account_sender_id' => $sender->id
            ]);
        }
    }
}
