<?php

class Plan
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

        return $handler->plan()->get()->send();
    }

    /**
     * @return array
     */
    function create()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'name' => 'Plano via PayUnity',
            'amount' => '1000.00',
            'currency' => 'BRL',
            'payment_method' => 'credit_card',
            'billing_type' => 'prepaid',
            'interval_type' => 'day',
            'interval_count' => '30',
            'trial_period_days' => null,
            'description' => 'Plano de teste criado via PayUnity',
            'statement_descriptor' => 'PayUnity Subscription Plan',
        ];

        return $handler->plan()->create($data)->send();
    }

    /**
     * @return array
     */
    function update()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'name' => 'Plano via PayUnity',
            'amount' => '1000.00',
            'currency' => 'BRL',
            'payment_method' => 'credit_card',
            'billing_type' => 'prepaid',
            'interval_type' => 'day',
            'interval_count' => '30',
            'trial_period_days' => '7',
            'description' => 'Plano de teste criado via PayUnity',
            'statement_descriptor' => 'PayUnity Subscription Plan',
        ];

        return $handler->plan()->update('GATEWAY_PLAN_ID',$data)->send();
    }

    /**
     * @return array
     */
    function delete()
    {
        $handler = $this->initInstance();

        return $handler->plan()->delete('GATEWAY_PLAN_ID')->send();
    }
}
