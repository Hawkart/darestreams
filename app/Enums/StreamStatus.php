<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class StreamStatus extends Enum implements LocalizedEnum
{
    const Created = 0;
    const Active = 1;
    const Canceled = 2;
    const FinishedWaitPay = 3;
    const FinishedIsPayed = 4;
}
