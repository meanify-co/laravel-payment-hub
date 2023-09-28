<?php

class Customer
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
    function getByCustomerEmail()
    {
        $handler = $this->initInstance();

        return $handler->customer()->get(null, 'customer@email.com')->send();
    }

    /**
     * @return array
     */
    function getByCustomerInternalCode()
    {
        $handler = $this->initInstance();

        return $handler->customer()->get('000001')->send();
    }

    /**
     * @return array
     * @throws Exception
     */
    function create()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'first_name' => 'Fulano',
            'last_name' => 'de Tal',
            'document_type' => 'cpf',
            'document_number' => '00011122233',
            'email' => 'customer@example.com',
            'birth_date' => '1990-01-01',
            'address' => (object) [
                'street' => 'Av XYZ',
                'street_number' => '100',
                'neighborhood' => 'Centro',
                'zipcode' => '01100000',
                'city' => 'São Paulo',
                'state_code' => 'SP',
                'country_code' => 'BR',
            ],
            'phone' => (object) [
                'country_code' => '+55',
                'area_code' => '11',
                'number' => '999999999',
            ],
        ];

        return $handler->customer()->create($data)->send();
    }

    /**
     * @return array
     * @throws Exception
     */
    function update()
    {
        $handler = $this->initInstance();

        $getCustomer = $this->getByCustomerEmail();

        $customer = $getCustomer['result']->data[0];

        $data = (object) [
            'first_name' => 'Fulano',
            'last_name' => 'de Tal',
            'document_type' => 'cpf',
            'document_number' => '00011122233',
            'email' => 'customer@example.com',
            'birth_date' => '1990-01-01',
            'address' => (object) [
                'street' => 'Av XYZ',
                'street_number' => '100',
                'neighborhood' => 'Centro',
                'zipcode' => '01100000',
                'city' => 'São Paulo',
                'state_code' => 'SP',
                'country_code' => 'BR',
            ],
            'phone' => (object) [
                'country_code' => '+55',
                'area_code' => '11',
                'number' => '999999999',
            ],
        ];

        return $handler->customer()->update($customer->id, $data)->send();
    }
}
