<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TaskStatus extends Enum
{
    const Created = 0;
    const CheckedMediator = 1;
    const Active = 2;
    const IntervalFinishedAllowVote = 3;
    const AllowVote = 4;
    const VoteFinished = 5;
    const PayFinished = 6;
    const Canceled = 7;
}
