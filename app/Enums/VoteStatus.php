<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Contracts\LocalizedEnum;

final class VoteStatus extends Enum implements LocalizedEnum
{
    const Pending = 0;
    const Yes = 1;
    const No = 2;
}
