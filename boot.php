<?php

if (! function_exists('meanify_payment_hub'))
{
    /**
     * @param string $gatewayActiveKey mercado-pago|mercadopago|pagarme
     * @param string $gatewayVersion v1|v5
     * @param string $gatewayEnvironment live|sandbox
     * @param array $gatewayParams
     * @return \Meanify\LaravelPaymentHub\Factory
     */
    function meanify_payment_hub(string $gatewayActiveKey, string $gatewayVersion, string $gatewayEnvironment, array $gatewayParams = [])
    {
        return app('meanify_payment_hub', [
            'gatewayActiveKey'   => $gatewayActiveKey,
            'gatewayVersion'     => $gatewayVersion,
            'gatewayEnvironment' => $gatewayEnvironment,
            'gatewayParams'      => $gatewayParams,
        ]);
    }
}