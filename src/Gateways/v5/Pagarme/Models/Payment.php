<?php

namespace Meanify\LaravelPaymentHub\Gateways\v5\Pagarme\Models;

use Carbon\Carbon;
use Meanify\LaravelPaymentHub\Constants;
use Meanify\LaravelPaymentHub\Interfaces\ModelPaymentInterface;
use Meanify\LaravelPaymentHub\Utils\Helpers;
use multitypetest\model\Car;

class Payment implements ModelPaymentInterface
{
    /**
     * @param $paymentId
     * @return array
     */
    public function get($paymentId = null)
    {
        $result = [];

        return [
            'method' => Constants::$REQUEST_METHOD_GET,
            'uri' => 'orders/'.$paymentId,
            'result' => $result
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public function createCreditCardTransaction($data)
    {
        //Main data
        $payment = new \stdClass();
        $payment->code           = isset($data->internal_code) ? $data->internal_code : '';
        $payment->payment_method = 'credit_card';
        $payment->customer_id    = $data->gateway_customer_id;

        //Items
        $items = [];
        $item  = new \stdClass();
        $item->code        = $payment->code;
        $item->description = isset($data->description) ? $data->description : '';
        $item->quantity    = 1;
        $item->amount      = Helpers::removeMask($data->amount);
        $items[] = $item;
        $payment->items = $items;

        //Metadata
        if(isset($data->metadata))
        {
            $payment->metadata = $data->metadata;
        }

        //Card header
        $card = new \stdClass();
        $card->operation_type       = 'auth_and_capture';
        $card->installments         = $data->installments;


        //Card uses card_id
        if(isset($data->gateway_card_id))
        {
            $card->card_id = $data->gateway_card_id;
        }
        //Card with new data
        else
        {
            $billingAddress  = new \stdClass();
            $billingAddress->line_1   = $data->billing_address->street_number.','.$data->billing_address->street.','.$data->billing_address->neighborhood;;
            $billingAddress->line_2   = isset($data->billing_address->complement) ? $data->billing_address->complement : '';
            $billingAddress->zip_code = Helpers::removeMask($data->billing_address->postal_code);
            $billingAddress->city     = $data->billing_address->city;
            $billingAddress->state    = strtoupper($data->billing_address->state_code);
            $billingAddress->country  = strtoupper($data->billing_address->country_code);

            $newCard = new \stdClass();
            $newCard->number      = str_replace(' ','',$data->number);
            $newCard->holder_name = strtoupper($data->holder_name);
            $newCard->exp_month   = Carbon::createFromFormat('Y-m-d',$data->expiration_date.'-01')->format('m');
            $newCard->exp_year    = Carbon::createFromFormat('Y-m-d',$data->expiration_date.'-01')->format('Y');
            $newCard->cvv         = $data->cvv;
            $newCard->billing_address = $billingAddress;

            $card->card = $newCard;
        }

        //Set payment method (credit_card) for create transaction
        $payable = new \stdClass();
        $payable->payment_method  = 'credit_card';
        $payable->amount          = Helpers::removeMask($data->amount);
        $payable->credit_card     = $card;
        $payment->payments        = [$payable];
        
        return [
            'method' => Constants::$REQUEST_METHOD_POST,
            'uri' => 'orders',
            'result' => $payment
        ];
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function createDebitCardTransaction($data)
    {
        throw new \Exception('Method Payment::createDebitCardTransaction not allowed for Pagarme/v5');
    }

    /**
     * @param $data
     * @return array
     */
    public function createPixTransaction($data)
    {
        //Main data
        $payment = new \stdClass();
        $payment->code           = isset($data->internal_code) ? $data->internal_code : '';
        $payment->payment_method = 'pix';
        $payment->customer_id    = $data->gateway_customer_id;

        //Items
        $items = [];
        $item  = new \stdClass();
        $item->code        = $payment->code;
        $item->description = isset($data->description) ? $data->description : '';
        $item->quantity    = 1;
        $item->amount      = Helpers::removeMask($data->amount);
        $items[] = $item;
        $payment->items = $items;

        //Metadata
        if(isset($data->metadata))
        {
            $payment->metadata = $data->metadata;
        }

        //Pix info
        $pix             = new \stdClass();
        $pix->expires_at = Carbon::now()->addSeconds(60 * 60 * $data->pix_expiration_hours)->format('Y-m-d H:i:s');

        //Set payment method (pix) for create transaction
        $payable = new \stdClass();
        $payable->payment_method  = 'pix';
        $payable->amount          = Helpers::removeMask($data->amount);
        $payable->pix             = $pix;
        $payment->payments        = [$payable];

        return [
            'method' => Constants::$REQUEST_METHOD_POST,
            'uri' => 'orders',
            'result' => $payment
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public function createBankSlipTransaction($data)
    {
        throw new \Exception('Method Payment::createBankSlipTransaction not allowed for Pagarme/v5');
    }

    /**
     * @param $paymentId
     * @return array
     */
    public function refundCreditCardTransaction($paymentData)
    {
        if(!isset($paymentData->charges))
        {
            throw new \Exception('Payment not have paid transaction');
        }
        else if(count($paymentData->charges) == 0)
        {
            throw new \Exception('Payment not have paid transaction');
        }

        $charges = $paymentData->charges;

        $lastCharge = $paymentData->charges[count($charges) - 1];

        if($lastCharge->status != 'paid')
        {
            throw new \Exception('Payment status not paid');
        }

        return [
            'method' => Constants::$REQUEST_METHOD_DELETE,
            'uri' => "charges/$lastCharge->id",
            'result' => []
        ];
    }

    /**
     * @notes $paymentId is required by Interface, but not used in this function
     * @param $paymentId
     * @param $paymentData
     * @return array
     */
    public function getPayableFromPaidTransaction($paymentId = null, $paymentData = null)
    {
        if(!isset($paymentData->charges))
        {
            throw new \Exception('Payment not have paid transaction');
        }
        else if(count($paymentData->charges) == 0)
        {
            throw new \Exception('Payment not have paid transaction');
        }

        $charges = $paymentData->charges;

        $lastChargeId = $paymentData->charges[count($charges) - 1]->id;

        return [
            'method' => Constants::$REQUEST_METHOD_GET,
            'uri' => "payables?charge_id=$lastChargeId",
            'result' => []
        ];
    }

    /**
     * @notes $paymentId is required by Interface, but not used in this function
     * @param $paymentId
     * @param $paymentData
     * @return \stdClass
     */
    public function getPixInfoFromPixTransaction($paymentId = null, $paymentData = null)
    {
        if(!isset($paymentData->charges[0]->last_transaction))
        {
            throw new \Exception('Pix transaction not found in this payment');
        }

        $transaction = $paymentData->charges[0]->last_transaction;

        $result = new \stdClass();
        $result->qr_code     = $transaction->qr_code;
        $result->qr_code_url = $transaction->qr_code_url;
        $result->expires_at  = Carbon::parse($transaction->expires_at)->format('Y-m-d H:i:s');

        return $result;
    }

    /**
     * @param $paymentData
     * @return array
     */
    public function cancelPaymentCharge($paymentData)
    {
        if(!isset($paymentData->charges))
        {
            throw new \Exception('Payment not have paid transaction');
        }
        else if(count($paymentData->charges) == 0)
        {
            throw new \Exception('Payment not have paid transaction');
        }

        $charges = $paymentData->charges;

        $lastCharge = $paymentData->charges[count($charges) - 1];

        if($lastCharge->status != 'pending' and $lastCharge->status != 'waiting_payment')
        {
            throw new \Exception('Payment status not allowed for this action');
        }

        return [
            'method' => Constants::$REQUEST_METHOD_DELETE,
            'uri' => "charges/$lastCharge->id",
            'result' => []
        ];
    }
}
