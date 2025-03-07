<?php

namespace Meanify\LaravelPaymentHub\Gateways\v1\MercadoPago\Models;

use Meanify\LaravelPaymentHub\Constants;
use Meanify\LaravelPaymentHub\Interfaces\ModelPaymentMethodInterface;

class PaymentMethod implements ModelPaymentMethodInterface
{
    /**
     * @return mixed
     * @throws \Exception
     */
    public function get()
    {
        return [
            'method' => Constants::$REQUEST_METHOD_GET,
            'uri' => 'payment_methods',
            'result' => []
        ];
    }
}
