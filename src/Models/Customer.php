<?php

namespace Meanify\LaravelPaymentHub\Models;

use Meanify\LaravelPaymentHub\Client;
use Meanify\LaravelPaymentHub\HandleResult;
use Meanify\LaravelPaymentHub\Interfaces\ModelCustomerInterface;
use Meanify\LaravelPaymentHub\Utils\Validator;

class Customer implements ModelCustomerInterface
{
    use Client, HandleResult;

    private $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param $customerId
     * @return $this
     */
    public function find($customerId)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Customer','find')->call($customerId);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $customerId
     * @param $customerEmail
     * @return $this
     */
    public function get($customerId = null, $customerEmail = null)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Customer','get')->call($customerId, $customerEmail);

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
