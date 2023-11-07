<?php

namespace MindApps\LaravelPayUnity\Models;

use MindApps\LaravelPayUnity\Client;
use MindApps\LaravelPayUnity\HandleResult;
use MindApps\LaravelPayUnity\Interfaces\ModelCustomerInterface;
use MindApps\LaravelPayUnity\Utils\Validator;

class Customer implements ModelCustomerInterface
{
    use Client, HandleResult;

    private $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param $customerInternalCode
     * @param $customerEmail
     * @return $this
     */
    public function get($customerInternalCode = null, $customerEmail = null)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Customer','get')->call($customerInternalCode, $customerEmail);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $data
     * @return $this
     * @throws \Exception
     */
    public function create($data)
    {
        $validator = Validator::customerData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Customer','create')->call($data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $customerId
     * @param $data
     * @return $this
     * @throws \Exception
     */
    public function update($customerId, $data)
    {
        $validator = Validator::customerData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Customer','update')->call($customerId, $data);

        $this->setApiRequest($apiRequest);

        return $this;
    }
}
