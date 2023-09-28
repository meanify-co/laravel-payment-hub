<?php

class Subscription
{
    /**
     * @return \MindApps\LaravelPayUnity\Factory
     */
    function initInstance()
    {
        return new \MindApps\LaravelPayUnity\Factory(
            'pagarme',
            'v5',
            'sandbox',
            [
                'secret_key' => 'SECRET_KEY_FROM_PAGARME'
            ]
        );

    }

    /**
     * @return array
     */
    function get()
    {
        $handler = $this->initInstance();

        return $handler->subscription()->get()->send();
    }

    /**
     * @return array
     */
    function create()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'internal_code' => '',
            'gateway_customer_id' => 'CUSTOMER_ID',
            'gateway_plan_id' => 'PLAN_ID',
            'gateway_card_id' => 'CARD_ID',
        ];

        return $handler->subscription()->create($data)->send();
    }

    /**
     * @return array
     */
    function updateCreditCard()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'gateway_card_id' => 'CARD_ID',
        ];

        return $handler->subscription()->updateCreditCard('GATEWAY_SUBSCRIPTION_ID',$data)->send();
    }

    /**
     * @return array
     */
    function updateMetadata()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'metadata' => [
                'Origin' => 'Localhost',
                'Sandbox' => '1'
            ]
        ];

        return $handler->subscription()->updateMetadata('GATEWAY_SUBSCRIPTION_ID',$data)->send();
    }

    /**
     * @return array
     */
    function cancel()
    {
        $handler = $this->initInstance();

        return $handler->subscription()->cancel('GATEWAY_SUBSCRIPTION_ID')->send();
    }

    /**
     * @return array
     */
    function enableManualBilling()
    {
        $handler = $this->initInstance();

        return $handler->subscription()->enableManualBilling('GATEWAY_SUBSCRIPTION_ID')->send();
    }

    /**
     * @return array
     */
    function disableManualBilling()
    {
        $handler = $this->initInstance();

        return $handler->subscription()->disableManualBilling('GATEWAY_SUBSCRIPTION_ID')->send();
    }
}
