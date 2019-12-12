<?php

namespace Dare\Services;

class PaymentService
{
    /**
     * PaymentService constructor.
     * @param string $gateway
     */
    public static function init(string $gateway)
    {
        $gateway = ucfirst(strtolower($gateway));
        $class = "\Dare\Payments\\" . $gateway;

        return new $class();
    }
}