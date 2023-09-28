<?php

namespace MindApps\LaravelPayUnity\Gateways\v5\Pagarme\Models;

use Carbon\Carbon;
use MindApps\LaravelPayUnity\Interfaces\ModelPostbackInterface;

class Postback implements ModelPostbackInterface
{
    /**
     * @param $postback
     * @return \stdClass
     */
    public function handle($postback)
    {
        $result = new \stdClass();
        $result->type   = null;
        $result->model  = null;
        $result->update = null;

        if(is_string($postback))
        {
            $postback = json_decode($postback);
        }

        list($postbackTypeEntity, $postbackTypeEvent) = explode('.', $postback->type);

        $availableEntities = ['order','charge'];

        if(!in_array($postbackTypeEntity, $availableEntities))
        {
            throw new \Exception('This postback ['.$postback->type.'] is not configured for this version');
        }
        else
        {
            $data = $postback->data;

            if(in_array($postbackTypeEntity, ['order','charge']))
            {
                $result->model = 'payment';

                if($postbackTypeEntity == 'order')
                {
                    $data->order = clone $data;
                }


                $result->update = new \stdClass();
                $result->update->newPaymentStatus    = null;
                $result->update->eventDisplayMessage = null;
                $result->update->paymentMethod       = null;
                $result->update->paymentDate         = null; //date of payment returned by postback, case new transaction status equals "paid"
                $result->update->amountPaid          = null; //amount paid returned by postback, case new transaction status equals "paid"
                $result->update->reason              = null; //reason of refuse (cancel) return by postback, case new transaction status equals "refused" (canceled)
                $result->update->card                = null; //credit card info will be stored into transaction event, case payment method equals "credit_card"
                $result->update->bankSlip            = null; //billet info will be stored into transaction event, case payment method equals "billet"
                $result->update->pix                 = null; //pix info will be stored into transaction event, case payment method equals "pix"

                
                //----------------------------------------------------------------------------------------------------//
                // Unhandled postbackTypes:
                // - charge.created: the charge was create on create of transaction
                // - charge.updated: the transaction updated by charge status changed
                // - charge.underpaid: we do not have handling for this type of event
                // - charge.overpaid: we do not have handling for this type of event
                // - charge.partial_canceled: we do not have handling for this type of event
                // - charge.chargedback: we do not have handling for this type of event
                // - order.paid: not update transaction status, because the transaction status is updated by charged.paid
                // - order.payment_failed: not update transaction status, because the transaction status is updated by
                //   charged.payment_failed
                // - order.canceled: not update transaction status, because the transaction status is updated by
                //   charged.payment_failed
                //----------------------------------------------------------------------------------------------------//
                if($postback->type == 'charge.paid')
                {
                    $result->type  = 'payment_update_status.paid';

                    $result->update->newPaymentStatus    = 'paid';
                    $result->update->eventDisplayMessage = 'Payment confirmed by Pagar.me';
                    $result->update->paymentMethod       = $data->payment_method;
                    $result->update->amountPaid          = substr($data->paid_amount, 0, -2).'.'.substr($data->paid_amount, -2);
                    $result->update->paymentDate         = Carbon::parse($data->paid_at)->format('Y-m-d');

                    if($result->update->paymentMethod == 'credit_card')
                    {
                        $result->update->card = new \stdClass();
                        $result->update->card->card_holder_name = $data->last_transaction->card->holder_name;
                        $result->update->card->card_brand       = strtolower($data->last_transaction->card->brand);
                        $result->update->card->card_last_digits = $data->last_transaction->card->last_four_digits;
                    }
                }
                elseif(in_array($postback->type,['charge.payment_failed','charge.payment_refused','order.payment_failed']))
                {
                    $result->type  = 'payment_update_status.failed';

                    if(!isset($data->last_transaction))
                    {
                        $data->last_transaction = $data->charges[ count($data->charges) - 1 ]->last_transaction;
                    }

                    $result->update->newPaymentStatus    = 'failed';
                    $result->update->eventDisplayMessage = 'Payment declined by Pagar.me';
                    $result->update->reason              = isset($data->last_transaction->acquirer_return_code) ? $this->getRefuseReasonByCode($data->last_transaction->acquirer_return_code) : json_encode($data->last_transaction->gateway_response, 256);
                    $result->update->paymentMethod       = isset($data->last_transaction->transaction_type) ? $data->last_transaction->transaction_type : null;

                    if($result->update->paymentMethod == 'credit_card')
                    {
                        $result->update->card = new \stdClass();
                        $result->update->card->card_holder_name = isset($data->last_transaction->card) ? $data->last_transaction->card->holder_name : null;
                        $result->update->card->card_brand       = isset($data->last_transaction->card) ? strtolower($data->last_transaction->card->brand) : null;
                        $result->update->card->card_last_digits = isset($data->last_transaction->card) ? $data->last_transaction->card->last_four_digits : null;
                    }
                }
                elseif($postback->type == 'charge.refunded')
                {
                    $result->type  = 'payment_update_status.refunded';

                    $result->update->newPaymentStatus    = 'refunded';
                    $result->update->eventDisplayMessage = 'Payment refunded by Pagar.me';
                }
                elseif(in_array($postback->type,['charge.pending','charge.waiting_payment']))
                {
                    $result->type  = 'payment_update_status.waiting_payment';

                    if($data->payment_method == 'pix')
                    {
                        $result->update->newPaymentStatus    = 'waiting_payment';
                        $result->update->eventDisplayMessage = 'QR code for payment generated by Pagar.me';
                        $result->update->paymentMethod       = 'pix';

                        $result->update->pix             = new \stdClass();
                        $result->update->pix->qrCode     = $data->last_transaction->qr_code;
                        $result->update->pix->qrCodeUrl  = $data->last_transaction->qr_code_url;
                        $result->update->pix->expireAt   = $data->last_transaction->expires_at;
                    }
                    else
                    {
                        $result->update->newPaymentStatus    = 'waiting_payment';
                        $result->update->eventDisplayMessage = 'New payment process was registered at Pagar.me';
                        $result->update->paymentMethod       = $data->payment_method;
                    }
                }
                else
                {
                    throw new \Exception('This postback ['.$postback->type.'] is not configured for this version');
                }
            }
        }

        return $result;
    }

    /**
     * @param $code
     * @return string
     */
    public function getRefuseReasonByCode($code)
    {
        $reasons = [
            '1000' => 'Transaction not authorized',
            '1004' => 'Card with restrictions',
            '1009' => 'Transaction not authorized',
            '1001' => 'Expired card',
            '1011' => 'Incorrect card number entered',
            '1016' => 'Insufficient balance',
            '2002' => 'Transaction suspected of fraud',
            'antifraud' => 'Rejected by antifraud',
        ];

        $reasonText = 'It was not possible to complete the payment. Please verify the information and try again. [code '.$code.']';

        if(array_key_exists($code, $reasons))
        {
            $reasonText = $reasons[$code];
        }

        return $reasonText;
    }
}
