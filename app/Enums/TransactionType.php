<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class TransactionType extends Enum
{
    const Deposit = 0;
    const Donation = 1;
    const Withdraw = 2;
}
