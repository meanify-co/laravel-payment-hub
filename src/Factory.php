<?php

namespace MindApps\LaravelPayUnity;

use MindApps\LaravelPayUnity\Utils\Helpers;
use MindApps\LaravelPayUnity\Utils\Validator;
use MindApps\LaravelPayUnity\Utils\Formatter;

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
     * @param $gatewayActiveKey
     * @param $gatewayVersion
     * @param $gatewayEnvironment
     * @param $gatewayParams
     */
    public function __construct($gatewayActiveKey, $gatewayVersion, $gatewayEnvironment, $gatewayParams = [])
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
