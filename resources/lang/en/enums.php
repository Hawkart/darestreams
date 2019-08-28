<?php

use App\Enums\StreamStatus;
use App\Enums\TaskStatus;
use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Enums\VoteStatus;

return [
    StreamStatus::class => [
        StreamStatus::Created => 'Created',
        StreamStatus::Active => 'Active',
        StreamStatus::Canceled => 'Canceled',
        StreamStatus::FinishedWaitPay => 'Finished, wait for pay',
        StreamStatus::FinishedIsPayed => 'Finished and payed'
    ],
    TaskStatus::class => [
        TaskStatus::Created => 'Created',
        TaskStatus::CheckedMediator => 'Checked by mediator',
        TaskStatus::Active => 'Active',
        TaskStatus::IntervalFinishedAllowVote => 'Interval finished, allowed to vote',
        TaskStatus::AllowVote => 'Allow vote',
        TaskStatus::VoteFinished => 'Vote finished',
        TaskStatus::PayFinished => 'Pay finished',
        TaskStatus::Canceled => 'Canceled'
    ],
    TransactionStatus::class => [
        TransactionStatus::Created => 'Created',
        TransactionStatus::Holding => 'Holding',
        TransactionStatus::Completed => 'Completed',
        TransactionStatus::Canceled => 'Canceled'
    ],
    TransactionType::class => [
        TransactionType::Deposit => 'Deposit',
        TransactionType::Donation => 'Donation',
        TransactionType::Withdraw => 'Withdraw'
    ],
    VoteStatus::class => [
        VoteStatus::Pending => 'Pending',
        VoteStatus::Yes => 'Yes',
        VoteStatus::No => 'No'
    ]
];
