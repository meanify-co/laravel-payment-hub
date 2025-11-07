<?php

namespace Meanify\LaravelPaymentHub\Models;

use Meanify\LaravelPaymentHub\Client;
use Meanify\LaravelPaymentHub\Constants;
use Meanify\LaravelPaymentHub\HandleResult;
use Meanify\LaravelPaymentHub\Interfaces\ModelPaymentInterface;
use Meanify\LaravelPaymentHub\Utils\Helpers;
use Meanify\LaravelPaymentHub\Utils\Validator;

class Payment implements ModelPaymentInterface
{
    use Client, HandleResult;

    private $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    /**
     * @param $paymentId
     * @return $this
     */
    public function get($paymentId = null)
    {
        $apiRequest = $this->properties['gatewayInstance']->setMethod('Payment','get')->call($paymentId);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $data
     * @return $this
     * @throws \Exception
     */
    public function createCreditCardTransaction($data)
    {
        $validator = Validator::paymentCreditCardData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Payment','createCreditCardTransaction')->call($data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function createDebitCardTransaction($data)
    {
        $validator = Validator::paymentDebitCardData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Payment','createDebitCardTransaction')->call($data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function createPixTransaction($data)
    {
        $validator = Validator::paymentPixData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Payment','createPixTransaction')->call($data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function createBankSlipTransaction($data)
    {
        $validator = Validator::paymentBankSlipData($data, $this->properties['gatewayActiveName']);

        if(!$validator['success'])
        {
            throw new \Exception('Data properties has errors: '.$validator['errors']);
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Payment','createBankSlipTransaction')->call($data);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @param $paymentId
     * @return $this
     */
    public function refundCreditCardTransaction($paymentId)
    {
        $getData = $this->get($paymentId)->send();

        if(!$getData['success'])
        {
            throw new \Exception('Payment not found');
        }
        else
        {
            $paymentData = $getData['result'];
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Payment','refundCreditCardTransaction')->call($paymentData);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @notes If $paymentId is provided, we perform the data retrieval from the API and then handle it in the final function.
     *        In case $paymentData is provided instead of $paymentId, we directly pass the data to the final function.
     * @param $paymentId
     * @return mixed
     */
    public function getPayableFromPaidTransaction($paymentId = null, $paymentData = null)
    {
        if(Helpers::checkStringIsNull($paymentData))
        {
            $getData = $this->get($paymentId)->send();

            if(!$getData['success'])
            {
                throw new \Exception('Payment not found');
            }
            else
            {
                $paymentData = $getData['result'];
            }
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Payment','getPayableFromPaidTransaction')->call(null, $paymentData);

        $this->setApiRequest($apiRequest);

        return $this;
    }

    /**
     * @notes If $paymentId is provided, we perform the data retrieval from the API and then handle it in the final function.
     *        In case $paymentData is provided instead of $paymentId, we directly pass the data to the final function.
     *        The final function does not utilize the 'send' method, as it solely processes the data for a readable return.
     * @param $paymentId
     * @return mixed
     */
    public function getPixInfoFromPixTransaction($paymentId = null, $paymentData = null)
    {
        if(Helpers::checkStringIsNull($paymentData))
        {
            $getData = $this->get($paymentId)->send();

            if(!$getData['success'])
            {
                throw new \Exception('Payment not found');
            }
            else
            {
                $paymentData = $getData['result'];
            }
        }

        $result = $this->properties['gatewayInstance']->setMethod('Payment','getPixInfoFromPixTransaction')->call(null, $paymentData);

        return $result;
    }

    /**
     * @param $paymentId
     * @return $this
     */
    public function cancelPaymentCharge($paymentId)
    {
        $getData = $this->get($paymentId)->send();

        if(!$getData['success'])
        {
            throw new \Exception('Payment not found');
        }
        else
        {
            $paymentData = $getData['result'];
        }

        $apiRequest = $this->properties['gatewayInstance']->setMethod('Payment','cancelPaymentCharge')->call($paymentData);

        $this->setApiRequest($apiRequest);

        return $this;
    }
}
