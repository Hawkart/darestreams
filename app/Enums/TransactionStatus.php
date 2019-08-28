<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TransactionStatus extends Enum
{
    const Created = 0;
    const Holding = 1;
    const Completed = 2;
    const Canceled = 3;
}
