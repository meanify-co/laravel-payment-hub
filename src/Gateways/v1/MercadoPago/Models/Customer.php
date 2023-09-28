<?php

namespace MindApps\LaravelPayUnity\Gateways\v1\MercadoPago\Models;

use MindApps\LaravelPayUnity\Interfaces\ModelCustomerInterface;

class Customer implements ModelCustomerInterface
{
    /**
     * @param $customerInternalCode
     * @param $customerEmail
     * @return mixed
     * @throws \Exception
     */
    public function get($customerInternalCode = null, $customerEmail = null)
    {
        throw new \Exception('Method Customer::get not allowed for MercadoPago/v1');
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function create($data)
    {
        throw new \Exception('Method Customer::create not allowed for MercadoPago/v1');
    }

    /**
     * @param $customerId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function update($customerId, $data)
    {
        throw new \Exception('Method Customer::update not allowed for MercadoPago/v1');
    }
}
