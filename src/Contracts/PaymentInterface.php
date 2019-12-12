<?php

declare(strict_types=1);

namespace Dare\Contracts;
use Illuminate\Http\Request;

Interface PaymentInterface
{
    public function init();
    public function checkout(array $params);
    public function completed(Request $request);
}