<?php

namespace Meanify\LaravelPaymentHub\Gateways\v1\MercadoPago\Models;

use Meanify\LaravelPaymentHub\Interfaces\ModelPaymentInterface;

class Payment implements ModelPaymentInterface
{
    /**
     * @param $paymentId
     * @return mixed
     * @throws \Exception
     */
    public function get($paymentId = null)
    {
        throw new \Exception('Method Payment::get not allowed for MercadoPago/v1');
    }

    /**
     * @param $data
     * @return array
     */
    public function createCreditCardTransaction($data)
    {
        throw new \Exception('Method Payment::createCreditCardTransaction not allowed for MercadoPago/v1');
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function createDebitCardTransaction($data)
    {
        throw new \Exception('Method Payment::createDebitCardTransaction not allowed for MercadoPago/v1');
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function createPixTransaction($data)
    {
        throw new \Exception('Method Payment::createPixTransaction not allowed for MercadoPago/v1');
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function createBankSlipTransaction($data)
    {
        throw new \Exception('Method Payment::createPixTransaction not allowed for MercadoPago/v1');
    }

    /**
     * @param $paymentId
     * @return mixed
     * @throws \Exception
     */
    public function refundCreditCardTransaction($paymentId)
    {
        throw new \Exception('Method Payment::refundCreditCardTransaction not allowed for MercadoPago/v1');
    }

    /**
     * @param $paymentId
     * @param $paymentData
     * @return mixed
     * @throws \Exception
     */
    public function getPayableFromPaidTransaction($paymentId = null, $paymentData = null)
    {
        throw new \Exception('Method Payment::getPayableFromPaidTransaction not allowed for MercadoPago/v1');
    }

    /**
     * @param $paymentId
     * @param $paymentData
     * @return mixed
     * @throws \Exception
     */
    public function getPixInfoFromPixTransaction($paymentId = null, $paymentData = null)
    {
        throw new \Exception('Method Payment::getPixInfoFromPixTransaction not allowed for MercadoPago/v1');
    }
}
