<?php

namespace MindApps\LaravelPayUnity\Gateways\v1\MercadoPago\Models;

use MindApps\LaravelPayUnity\Interfaces\ModelPlanInterface;

class Plan implements ModelPlanInterface
{
    /**
     * @param $planId
     * @return mixed
     * @throws \Exception
     */
    public function get($planId = null)
    {
        throw new \Exception('Method Plan::get not allowed for MercadoPago/v1');
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function create($data)
    {
        throw new \Exception('Method Plan::create not allowed for MercadoPago/v1');
    }

    /**
     * @param $planId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function update($planId, $data)
    {
        throw new \Exception('Method Plan::update not allowed for MercadoPago/v1');
    }

    /**
     * @param $planId
     * @return mixed
     * @throws \Exception
     */
    public function delete($planId)
    {
        throw new \Exception('Method Plan::delete not allowed for MercadoPago/v1');
    }
}
