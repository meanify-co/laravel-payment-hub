<?php

namespace Meanify\LaravelPaymentHub\Models;

use Meanify\LaravelPaymentHub\Client;
use Meanify\LaravelPaymentHub\HandleResult;
use Meanify\LaravelPaymentHub\Interfaces\ModelCardInterface;
use Meanify\LaravelPaymentHub\Utils\Validator;

class Card implements ModelCardInterface
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
    public function get($customerId)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Card','get')->call($customerId);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $customerId
     * @param $data
     * @return $this
     */
    public function create($customerId, $data)
    {
        $validator = Validator::cardData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Card','create')->call($customerId, $data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $customerId
     * @param $cardId
     * @param $data
     * @return $this
     */
    public function update($customerId, $cardId, $data)
    {
        $validator = Validator::cardData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Card','update')->call($customerId, $cardId, $data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $customerId
     * @param $cardId
     * @return $this
     */
    public function delete($customerId, $cardId)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Card','delete')->call($customerId, $cardId);

        $this->setApiRequest($apiRequest);

        return $this;
    }
}
