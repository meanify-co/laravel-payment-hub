<?php

namespace MindApps\LaravelPayUnity\Gateways\v1\MercadoPago\Models;

use MindApps\LaravelPayUnity\Interfaces\ModelCardInterface;

class Card implements ModelCardInterface
{
    /**
     * @param $customerId
     * @return mixed
     * @throws \Exception
     */
    public function get($customerId)
    {
        throw new \Exception('Method Card::get not allowed for MercadoPago/v1');
    }

    /**
     * @param $customerId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function create($customerId, $data)
    {
        throw new \Exception('Method Card::create not allowed for MercadoPago/v1');
    }

    /**
     * @param $customerId
     * @param $cardId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function update($customerId, $cardId, $data)
    {
        throw new \Exception('Method Card::update not allowed for MercadoPago/v1');
    }

    /**
     * @param $customerId
     * @param $cardId
     * @return mixed
     * @throws \Exception
     */
    public function delete($customerId, $cardId)
    {
        throw new \Exception('Method Card::delete not allowed for MercadoPago/v1');
    }
}
