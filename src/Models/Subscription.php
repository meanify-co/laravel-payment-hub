<?php

namespace MindApps\LaravelPayUnity\Models;

use MindApps\LaravelPayUnity\Client;
use MindApps\LaravelPayUnity\HandleResult;
use MindApps\LaravelPayUnity\Interfaces\ModelSubscriptionInterface;
use MindApps\LaravelPayUnity\Utils\Validator;

class Subscription implements ModelSubscriptionInterface
{
    use Client, HandleResult;

    private $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param $subscriptionId
     * @return $this
     */
    public function get($subscriptionId = null)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Subscription','get')->call($subscriptionId);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function create($data)
    {
        $validator = Validator::subscriptionData($data);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Subscription','create')->call($data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $subscriptionId
     * @param $data
     * @return $this
     */
    public function updateCreditCard($subscriptionId, $data)
    {
        $validator = Validator::subscriptionCreditCardData($data);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Subscription','updateCreditCard')->call($subscriptionId, $data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $subscriptionId
     * @param $data
     * @return $this
     */
    public function updateMetadata($subscriptionId, $data)
    {
        $validator = Validator::subscriptionMetadataData($data);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Subscription','updateMetadata')->call($subscriptionId, $data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $subscriptionId
     * @return $this
     */
    public function cancel($subscriptionId)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Subscription','cancel')->call($subscriptionId);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $subscriptionId
     * @return $this
     */
    public function enableManualBilling($subscriptionId)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Subscription','enableManualBilling')->call($subscriptionId);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $subscriptionId
     * @return $this
     */
    public function disableManualBilling($subscriptionId)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Subscription','disableManualBilling')->call($subscriptionId);

        $this->setApiRequest($apiRequest);

        return $this;
    }
}
