<?php

namespace Meanify\LaravelPaymentHub\Interfaces;

interface GatewayHandlerInterface
{
    public function formatRequest($method, $uri, $result = []);

    public function setMethod($model, $method);

    public function call(...$args);
}
