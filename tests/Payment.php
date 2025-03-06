<?php

class Payment
{
    /**
     * @return \Meanify\LaravelPaymentHub\Factory
     */
    function initInstance()
    {
        return new \Meanify\LaravelPaymentHub\Factory(
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

        return $handler->payment()->get()->send();
    }

    /**
     * @return array
     */
    function createCreditCardTransactionWithCardId()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'internal_code'       => null,
            'description'         => 'Pagamento ref. assinatura',
            'gateway_customer_id' => 'GATEWAY_CUSTOMER_ID',
            'amount'              => '100.00',
            'metadata'            => [
                'transaction_id'   => '123',
                'transaction_code' => 'transaction_123',
            ],
            'installments' => 1,
            'gateway_card_id' => 'GATEWAY_CARD_ID',
        ];

        return $handler->payment()->createCreditCardTransaction($data)->send();
    }

    /**
     * @return array
     */
    function createCreditCardTransactionWithNewCard()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'internal_code'       => 'MY_CUSTOM_CODE', //is required for payment
            'description'         => 'Pagamento ref. assinatura',
            'gateway_customer_id' => 'GATEWAY_CUSTOMER_ID',
            'amount'              => '100.00',
            'metadata'            => [
                'transaction_id'   => '123',
                'transaction_code' => 'transaction_123',
            ],
            'installments' => 1,
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

        return $handler->payment()->createCreditCardTransaction($data)->send();
    }

    /**
     * @return array
     */
    function createPixTransaction()
    {
        $handler = $this->initInstance();

        $data = (object) [
            'internal_code'       => 'MY_CUSTOM_CODE', //is required for payment
            'description'         => 'Pagamento ref. assinatura',
            'gateway_customer_id' => 'GATEWAY_CUSTOMER_ID',
            'amount'              => '100.00',
            'metadata'            => [
                'transaction_id'   => '123',
                'transaction_code' => 'transaction_123',
            ],
            'pix_expiration_hours' => 4
        ];

        return $handler->payment()->createPixTransaction($data)->send();
    }

    /**
     * @return array
     */
    function refundCreditCardTransaction()
    {
        $handler = $this->initInstance();

        return $handler->payment()->refundCreditCardTransaction('GATEWAY_PAYMENT_ID')->send();
    }

    /**
     * @return array
     */
    function getPayableFromPaidTransaction()
    {
        $handler = $this->initInstance();

        return $handler->payment()->getPayableFromPaidTransaction('GATEWAY_PAYMENT_ID')->send();
    }

    /**
     * @return array
     */
    function getPixInfoFromPixTransaction()
    {
        $handler = $this->initInstance();

        return $handler->payment()->getPixInfoFromPixTransaction('GATEWAY_PAYMENT_ID');
    }
}
