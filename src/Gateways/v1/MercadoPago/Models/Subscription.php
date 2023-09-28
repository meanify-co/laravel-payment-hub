<?php

namespace MindApps\LaravelPayUnity\Gateways\v1\MercadoPago\Models;

use MindApps\LaravelPayUnity\Interfaces\ModelSubscriptionInterface;

class Subscription implements ModelSubscriptionInterface
{
    /**
     * @param $subscriptionId
     * @return mixed
     * @throws \Exception
     */
    public function get($subscriptionId = null)
    {
        throw new \Exception('Method Subscription::get not allowed for MercadoPago/v1');
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function create($data)
    {
        throw new \Exception('Method Subscription::create not allowed for MercadoPago/v1');
    }

    /**
     * @param $subscriptionId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function updateCreditCard($subscriptionId, $data)
    {
        throw new \Exception('Method Subscription::updateCreditCard not allowed for MercadoPago/v1');
    }

    /**
     * @param $subscriptionId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function updateMetadata($subscriptionId, $data)
    {
        throw new \Exception('Method Subscription::updateMetadata not allowed for MercadoPago/v1');
    }

    /**
     * @param $subscriptionId
     * @return mixed
     * @throws \Exception
     */
    public function cancel($subscriptionId)
    {
        throw new \Exception('Method Subscription::cancel not allowed for MercadoPago/v1');
    }

    /**
     * @param $subscriptionId
     * @return mixed
     * @throws \Exception
     */
    public function enableManualBilling($subscriptionId)
    {
        throw new \Exception('Method Subscription::enableManualBilling not allowed for MercadoPago/v1');
    }

    /**
     * @param $subscriptionId
     * @return mixed
     * @throws \Exception
     */
    public function disableManualBilling($subscriptionId)
    {
        throw new \Exception('Method Subscription::disableManualBilling not allowed for MercadoPago/v1');
    }
}
