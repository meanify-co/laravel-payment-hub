<?php

namespace MindApps\LaravelPayUnity\Models;

use MindApps\LaravelPayUnity\Client;
use MindApps\LaravelPayUnity\HandleResult;
use MindApps\LaravelPayUnity\Interfaces\ModelPostbackInterface;

class Postback implements ModelPostbackInterface
{
    use Client, HandleResult;

    private $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function handle($data)
    {
        $result = $this->properties['gatewayInstance']->setMethod('Postback','handle')->call($data);

        return $result;
    }

    /**
     * @param $code
     * @return mixed
     */
    public function getRefuseReasonByCode($code)
    {
        $result = $this->properties['gatewayInstance']->setMethod('Postback','getRefuseReasonByCode')->call($code);

        return $result;
    }
}
