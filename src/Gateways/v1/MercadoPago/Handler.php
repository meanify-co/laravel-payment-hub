<?php

namespace Meanify\LaravelPaymentHub\Gateways\v1\MercadoPago;

use Meanify\LaravelPaymentHub\Interfaces\GatewayHandlerInterface;

class Handler implements GatewayHandlerInterface
{
    public static $validEnvironments = ['live','sandbox'];
    public static $requiredParams = ['public_key','access_token'];

    private $model;
    private $method;

    public function __construct($environment, $params)
    {
        $this->baseUrl     = 'https://api.mercadopago.com/v1/';
        $this->environment = $environment;
        $this->params      = $params;
    }

    /**
     * @param $method
     * @param $uri
     * @param $result
     * @return \stdClass
     */
    public function formatRequest($method, $uri, $result = [])
    {
        $response = new \stdClass();
        $response->baseUrl     = $this->baseUrl;
        $response->uri         = $uri;
        $response->endpoint    = $response->baseUrl . $response->uri;
        $response->method      = strtoupper($method);
        $response->environment = $this->environment;
        $response->headers     = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'authorization' => 'Bearer '.$this->params['access_token'],
        ];
        $response->body = json_encode($result,256);
        return $response;
    }

    /**
     * @param $model
     * @param $method
     * @return $this
     */
    public function setMethod($model, $method)
    {
        $this->model  = $model;
        $this->method = $method;
        return $this;
    }

    /**
     * @param ...$args
     * @return mixed|\stdClass
     */
    public function call(...$args)
    {
        $modelNamespace = '\Meanify\LaravelPaymentHub\Gateways\v1\MercadoPago\\Models\\'.$this->model;

        $modelInstance  = new $modelNamespace();

        $response = $modelInstance->{$this->method}(...$args);

        try
        {
            return $this->formatRequest($response['method'], $response['uri'], $response['result']);
        }
        catch (\Throwable $e)
        {
            return $response;
        }
    }
}
