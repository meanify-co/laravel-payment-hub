<?php

namespace Meanify\LaravelPaymentHub\Models;

use Meanify\LaravelPaymentHub\Client;
use Meanify\LaravelPaymentHub\HandleResult;
use Meanify\LaravelPaymentHub\Interfaces\ModelCardInterface;
use Meanify\LaravelPaymentHub\Interfaces\ModelPaymentMethodInterface;
use Meanify\LaravelPaymentHub\Utils\Validator;

class PaymentMethod implements ModelPaymentMethodInterface
{
    use Client, HandleResult;

    private $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @return $this
     */
    public function get()
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('PaymentMethod','get')->call();

        $this->setApiRequest($apiRequest);

        return $this;
    }
}
