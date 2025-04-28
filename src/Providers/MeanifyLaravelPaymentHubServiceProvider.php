<?php

namespace Meanify\LaravelPaymentHub\Providers;

use Illuminate\Support\ServiceProvider;

class MeanifyLaravelPaymentHubServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        if (!function_exists('meanify_payment_hub')) {
            require_once __DIR__ . '/../../boot.php';
        }
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->singleton('meanify_payment_hub', function($app, $params) {
            return new \Meanify\LaravelPaymentHub\Factory($params['gatewayActiveKey'], $params['gatewayVersion'], $params['gatewayEnvironment'], $params['gatewayParams']);
        });
    }
}
