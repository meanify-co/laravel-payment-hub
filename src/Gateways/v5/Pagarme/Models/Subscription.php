<?php

namespace MindApps\LaravelPayUnity\Gateways\v5\Pagarme\Models;

use MindApps\LaravelPayUnity\Interfaces\ModelSubscriptionInterface;

class Subscription implements ModelSubscriptionInterface
{
    /**
     * @param $subscriptionId
     * @return array
     */
    public function get($subscriptionId = null)
    {
        $result = [];

        return [
            'method' => 'GET',
            'uri' => 'subscriptions/'.$subscriptionId,
            'result' => $result
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $subscription = new \stdClass();
        $subscription->code           = isset($data->internal_code) ? $data->internal_code : '';
        $subscription->customer_id    = $data->gateway_customer_id;
        $subscription->plan_id        = $data->gateway_plan_id;
        $subscription->card_id        = $data->gateway_card_id;
        $subscription->payment_method = 'credit_card';
        $subscription->installments   = 1;
        $subscription->card           = null;

        return [
            'method' => 'POST',
            'uri' => 'subscriptions',
            'result' => $subscription
        ];
    }

    /**
     * @param $subscriptionId
     * @param $data
     * @return array
     */
    public function updateCreditCard($subscriptionId, $data)
    {
        $subscription = new \stdClass();
        $subscription->card_id = $data->gateway_card_id;

        return [
            'method' => 'PATCH',
            'uri' => 'subscriptions/'.$subscriptionId.'/card',
            'result' => $subscription
        ];
    }

    /**
     * @param $subscriptionId
     * @param $data
     * @return array
     */
    public function updateMetadata($subscriptionId, $data)
    {
        $metadata = new \stdClass();
        $metadata->metadata = $data->metadata;

        return [
            'method' => 'PATCH',
            'uri' => 'subscriptions/'.$subscriptionId.'/metadata',
            'result' => $metadata
        ];
    }

    /**
     * @param $subscriptionId
     * @return array
     */
    public function cancel($subscriptionId)
    {
        $result = [];

        return [
            'method' => 'DELETE',
            'uri' => 'subscriptions/'.$subscriptionId,
            'result' => $result
        ];
    }

    /**
     * @param $subscriptionId
     * @return array
     */
    public function enableManualBilling($subscriptionId)
    {
        $result = [];

        return [
            'method' => 'POST',
            'uri' => 'subscriptions/'.$subscriptionId.'/manual-billing',
            'result' => $result
        ];
    }

    /**
     * @param $subscriptionId
     * @return array
     */
    public function disableManualBilling($subscriptionId)
    {
        $result = [];

        return [
            'method' => 'DELETE',
            'uri' => 'subscriptions/'.$subscriptionId.'/manual-billing',
            'result' => $result
        ];
    }
}
