<?php

namespace Meanify\LaravelPaymentHub\Gateways\v1\MercadoPago\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Meanify\LaravelPaymentHub\Constants;
use Meanify\LaravelPaymentHub\Interfaces\ModelPaymentInterface;
use Meanify\LaravelPaymentHub\Utils\Helpers;

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
        $payment = new \stdClass();
        $payment->binary_mode           = !is_null($data->binary_mode) ? $data->binary_mode : true;
        $payment->description           = $data->description ?? null;
        $payment->statement_descriptor  = $data->statement_descriptor ?? null;
        $payment->notification_url      = $data->webhook ?? null;
        $payment->token                 = $data->gateway_card_token;
        $payment->transaction_amount    = (float) $data->amount;
        $payment->installments          = $data->installments;

        //Payer
        $payer = new \stdClass();
        $payer->id      = $data->gateway_customer_id;
        $payment->payer = $payer;

        //Metadata
        $metadata = [
            'code' => isset($data->internal_code) ? $data->internal_code : Str::uuid()->toString(),
        ];
        $metadata = array_merge($metadata, $data->metadata ?? []);
        $payment->metadata = $metadata;

        return [
            'method'  => Constants::$REQUEST_METHOD_POST,
            'uri'     => 'payments',
            'headers' => ['X-Idempotency-Key' => $data->internal_code],
            'result'  => $payment
        ];
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
