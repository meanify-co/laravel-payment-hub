<?php

namespace Meanify\LaravelPaymentHub;

use Meanify\LaravelPaymentHub\Utils\Helpers;
use Meanify\LaravelPaymentHub\Utils\Validator;
use Meanify\LaravelPaymentHub\Utils\Formatter;

class Factory
{
    use Methods;

    protected $gatewayActiveKey;
    protected $gatewayActiveName;
    protected $gatewayVersion;
    protected $gatewayEnvironment;
    protected $gatewayParams;
    protected $gatewayInstance;

    /**
     * @param string $gatewayActiveKey mercado-pago|mercadopago|pagarme
     * @param string $gatewayVersion v1|v5
     * @param string $gatewayEnvironment live|sandbox
     * @param array $gatewayParams
     */
    public function __construct(string $gatewayActiveKey, string $gatewayVersion, string $gatewayEnvironment, array $gatewayParams = [])
    {
        $this->setGatewayActive($gatewayActiveKey);
        $this->setGatewayVersion($gatewayVersion);
        $this->setGatewayEnvironment($gatewayEnvironment);
        $this->setGatewayParams($gatewayParams);
        $this->setInstance();
    }

    /**
     * @param $gatewayActiveKey
     * @return $this
     */
    private function setGatewayActive($gatewayActiveKey)
    {
        $this->gatewayActiveName = Validator::gatewayActive($gatewayActiveKey);

        $this->gatewayActiveKey  = $gatewayActiveKey;

        return $this;
    }

    /**
     * @param $gatewayVersion
     * @return $this
     */
    private function setGatewayVersion($gatewayVersion)
    {
        $this->gatewayVersion = Validator::gatewayVersion($this->gatewayActiveName, $gatewayVersion);

        return $this;
    }

    /**
     * @param $gatewayEnvironment
     * @return $this
     */
    private function setGatewayEnvironment($gatewayEnvironment)
    {
        $this->gatewayEnvironment = Validator::gatewayEnvironment($this->gatewayActiveName, $this->gatewayVersion, $gatewayEnvironment);

        return $this;
    }

    /**
     * @param $gatewayParams
     * @return $this
     */
    private function setGatewayParams($gatewayParams)
    {
        $this->gatewayParams = Validator::gatewayParams($this->gatewayActiveName, $this->gatewayVersion, $gatewayParams);

        return $this;
    }

    /**
     * @return $this
     */
    private function setInstance()
    {
        $gatewayPath = Helpers::getNamespace($this->gatewayActiveName, $this->gatewayVersion);

        $this->gatewayInstance = new $gatewayPath($this->gatewayEnvironment, $this->gatewayParams);

        return $this;
    }

    /**
     * @return array
     */
    private function getProperties()
    {
        $props = [];

        foreach($this as $key => $value)
        {
            $props[$key] = $value;
        }

        return $props;
    }



}
