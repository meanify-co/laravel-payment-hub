<?php

namespace Meanify\LaravelPaymentHub\Interfaces;

interface ModelPaymentInterface
{
    public function get($paymentId = null);

    public function createCreditCardTransaction($data);

    public function createDebitCardTransaction($data);

    public function createPixTransaction($data);

    public function createBankSlipTransaction($data);

    public function refundCreditCardTransaction($paymentId);

    public function getPayableFromPaidTransaction($paymentId = null, $paymentData = null);

    public function getPixInfoFromPixTransaction($paymentId = null, $paymentData = null);

}
