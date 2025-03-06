<?php

if (! function_exists('meanifyPaymentHub'))
{
    /**
     * @param string $gatewayActiveKey mercado-pago|mercadopago|pagarme
     * @param string $gatewayVersion v1|v5
     * @param string $gatewayEnvironment live|sandbox
     * @param array $gatewayParams
     * @return \Meanify\LaravelPaymentHub\Factory
     */
    function meanifyPaymentHub(string $gatewayActiveKey, string $gatewayVersion, string $gatewayEnvironment, array $gatewayParams = [])
    {
        return app('meanifyPaymentHub', [
            'gatewayActiveKey'   => $gatewayActiveKey,
            'gatewayVersion'     => $gatewayVersion,
            'gatewayEnvironment' => $gatewayEnvironment,
            'gatewayParams'      => $gatewayParams,
        ]);
    }
}