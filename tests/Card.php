<?php

class Card
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

        return $handler->card()->get('GATEWAY_CUSTOMER_ID')->send();
    }

    /**
     * @return array
     */
    function create()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'number' => '4024 0071 3701 5284',
            'holder_name' => 'Fulano de Tal',
            'expiration_date' => '2024-10',
            'cvv' => '336',
            'billing_address' => (object) [
                'street' => 'Av XYZ',
                'street_number' => '100',
                'neighborhood' => 'Centro',
                'zipcode' => '01100000',
                'city' => 'São Paulo',
                'state_code' => 'SP',
                'country_code' => 'BR',
            ]
        ];

        return $handler->card()->create('GATEWAY_CUSTOMER_ID', $data)->send();
    }

    /**
     * @return array
     */
    function delete()
    {
        $handler = $this->initInstance();

        return $handler->card()->delete('GATEWAY_CUSTOMER_ID', 'GATEWAY_CARD_ID')->send();
    }

}
