<?php

namespace Meanify\LaravelPaymentHub\Gateways\v1\MercadoPago\Models;

use Carbon\Carbon;
use Meanify\LaravelPaymentHub\Interfaces\ModelCardInterface;
use Meanify\LaravelPaymentHub\Utils\Helpers;

class Card implements ModelCardInterface
{
    /**
     * @param $customerId
     * @return mixed
     * @throws \Exception
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
     * @return mixed
     * @throws \Exception
     */
    public function create($customerId, $data)
    {
        $card = new \stdClass();
        $card->token = $data->card_token;
        
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
            'method' => 'DELETE',
            'uri' => 'customers/'.$customerId.'/cards/'.$cardId,
            'result' => $result
        ];
    }
}
