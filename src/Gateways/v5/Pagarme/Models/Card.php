<?php

namespace MindApps\LaravelPayUnity\Gateways\v5\Pagarme\Models;

use Carbon\Carbon;
use MindApps\LaravelPayUnity\Interfaces\ModelCardInterface;
use MindApps\LaravelPayUnity\Utils\Helpers;

class Card implements ModelCardInterface
{
    /**
     * @param $customerId
     * @return array
     */
    public function get($customerId)
    {
        $result = [];

        return [
            'method' => 'GET',
            'uri' => 'customers/'.$customerId.'/cards',
            'result' => $result
        ];
    }

    /**
     * @param $customerId
     * @param $data
     * @return array
     */
    public function create($customerId, $data)
    {
        $card = new \stdClass();
        $card->number      = str_replace(' ','',$data->number);
        $card->holder_name = strtoupper($data->holder_name);
        $card->exp_month   = Carbon::createFromFormat('Y-m-d',$data->expiration_date.'-01')->format('m');
        $card->exp_year    = Carbon::createFromFormat('Y-m-d',$data->expiration_date.'-01')->format('Y');
        $card->cvv         = $data->cvv;
        
        if(isset($data->billing_address))
        {
            $billingAddress  = new \stdClass();
            $billingAddress->line_1   = $data->billing_address->street_number.','.$data->billing_address->street.','.$data->billing_address->neighborhood;;
            $billingAddress->line_2   = isset($data->billing_address->complement) ? $data->billing_address->complement : '';
            $billingAddress->zip_code = Helpers::removeMask($data->billing_address->zipcode);
            $billingAddress->city     = $data->billing_address->city;
            $billingAddress->state    = strtoupper($data->billing_address->state_code);
            $billingAddress->country  = strtoupper($data->billing_address->country_code);
            $card->billing_address = $billingAddress;
        }

        return [
            'method' => 'POST',
            'uri' => 'customers/'.$customerId.'/cards',
            'result' => $card
        ];
    }

    /**
     * @param $customerId
     * @param $cardId
     * @param $data
     * @return array
     */
    public function update($customerId, $cardId, $data)
    {
        throw new \Exception('Method Card::update not allowed for Pagar.me/v5');
    }

    /**
     * @param $customerId
     * @param $cardId
     * @return array
     */
    public function delete($customerId, $cardId)
    {
        $result = [];

        return [
            'method' => 'DELETE',
            'uri' => 'customers/'.$customerId.'/cards/'.$cardId,
            'result' => $result
        ];
    }
}
