<?php

namespace MindApps\LaravelPayUnity\Gateways\v1\MercadoPago\Models;

use MindApps\LaravelPayUnity\Interfaces\ModelPostbackInterface;

class Postback implements ModelPostbackInterface
{
    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function handle($data)
    {
        throw new \Exception('Method Postback::handle not allowed for MercadoPago/v1');
    }

    /**
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    public function getRefuseReasonByCode($code)
    {
        throw new \Exception('Method Postback::getRefuseReasonByCode not allowed for MercadoPago/v1');
    }
}
