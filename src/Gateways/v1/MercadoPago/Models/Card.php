<?php

namespace Meanify\LaravelPaymentHub\Gateways\v1\MercadoPago\Models;

use Carbon\Carbon;
use Meanify\LaravelPaymentHub\Constants;
use Meanify\LaravelPaymentHub\Interfaces\ModelCardInterface;
use Meanify\LaravelPaymentHub\Utils\Helpers;

class Card implements ModelCardInterface
{

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function generateCardToken($data)
    {
        if(isset($data->card_id))
        {
            $card = new \stdClass();
            $card->card_id       = $data->card_id;
            $card->security_code = $data->cvv;
        }
        else
        {
            $holder = new \stdClass();
            $identification         = new \stdClass();
            $identification->type   = strtoupper($data->holder_document_type);
            $identification->number = Helpers::removeMask($data->holder_document_number);
            $holder->identification = $identification;
            $holder->name           = $data->holder_name ?? null;

            $card = new \stdClass();
            $card->card_number        = str_replace(' ','',$data->number);
            $card->expiration_month   = Carbon::createFromFormat('Y-m-d',$data->expiration_date.'-01')->format('m');
            $card->expiration_year    = Carbon::createFromFormat('Y-m-d',$data->expiration_date.'-01')->format('Y');
            $card->security_code      = $data->cvv;
            $card->payment_method_id  = $data->brand;
            $card->payment_type_id    = $data->card_type;
            $card->cardholder         = $holder;
        }

        return [
            'method' => Constants::$REQUEST_METHOD_POST,
            'uri' => 'card_tokens',
            'result' => $card
        ];
    }


    /**
     * @param $customerId
     * @param $cardId
     * @return array
     */
    public function find($customerId, $cardId)
    {
        return [
            'method' => Constants::$REQUEST_METHOD_GET,
            'uri' => 'customers/'.$customerId.'/cards/'.$cardId,
            'result' => []
        ];
    }


    /**
     * @param $customerId
     * @return mixed
     * @throws \Exception
     */
    public function get($customerId)
    {
        $result = [];

        return [
            'method' => Constants::$REQUEST_METHOD_GET,
            'uri' => 'customers/'.$customerId.'/cards',
            'result' => $result
        ];
    }

    /**
     * @param $customerId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function create($customerId, $data)
    {
        $card = new \stdClass();
        $card->token = $data->card_token;
        
        return [
            'method' => Constants::$REQUEST_METHOD_POST,
            'uri' => 'customers/'.$customerId.'/cards',
            'result' => $card
        ];
    }

    /**
     * @param $customerId
     * @param $cardId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function update($customerId, $cardId, $data)
    {
        throw new \Exception('Method Card::update not allowed for MercadoPago/v1');
    }

    /**
     * @param $customerId
     * @param $cardId
     * @return mixed
     * @throws \Exception
     */
    public function delete($customerId, $cardId)
    {
        $result = [];

        return [
            'method' => Constants::$REQUEST_METHOD_DELETE,
            'uri' => 'customers/'.$customerId.'/cards/'.$cardId,
            'result' => $result
        ];
    }
}
