<?php

namespace Meanify\LaravelPaymentHub;

class Constants
{
    public static $REQUEST_METHOD_POST       = 'POST';
    public static $REQUEST_METHOD_GET        = 'GET';
    public static $REQUEST_METHOD_PUT        = 'PUT';
    public static $REQUEST_METHOD_PATCH      = 'PATCH';
    public static $REQUEST_METHOD_DELETE     = 'DELETE';

    public static $MERCADO_PAGO_GATEWAY_NAME = 'MercadoPago';
    public static $PAGARME_GATEWAY_NAME      = 'Pagarme';

    /**
     * @var string[]
     */
    public static $VALID_GATEWAYS = [
        'mercado-pago'  => 'MercadoPago',
        'mercadopago'   => 'MercadoPago',
        'pagarme'       => 'Pagarme',
    ];

    /**
     * @var array
     */
    public static $NON_INTERFACE_FUNCTIONS_FOR_GATEWAYS = [
        'Card::generateCardToken' => [
            'MercadoPago@v1'
        ]
    ];
}