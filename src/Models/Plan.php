<?php

namespace MindApps\LaravelPayUnity\Models;

use MindApps\LaravelPayUnity\Client;
use MindApps\LaravelPayUnity\HandleResult;
use MindApps\LaravelPayUnity\Interfaces\ModelPlanInterface;
use MindApps\LaravelPayUnity\Utils\Validator;

class Plan implements ModelPlanInterface
{
    use Client, HandleResult;

    private $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param $planId
     * @return $this
     */
    public function get($planId = null)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Plan','get')->call($planId);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function create($data)
    {
        $validator = Validator::planData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Plan','create')->call($data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $planId
     * @param $data
     * @return $this
     */
    public function update($planId, $data)
    {
        $validator = Validator::planData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Plan','update')->call($planId, $data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $planId
     * @return $this
     */
    public function delete($planId)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Plan','delete')->call($planId);

        $this->setApiRequest($apiRequest);

        return $this;
    }
}
