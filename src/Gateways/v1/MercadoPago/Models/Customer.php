<?php

namespace Meanify\LaravelPaymentHub\Gateways\v1\MercadoPago\Models;

use Carbon\Carbon;
use Meanify\LaravelPaymentHub\Interfaces\ModelCustomerInterface;
use Meanify\LaravelPaymentHub\Utils\Helpers;

class Customer implements ModelCustomerInterface
{
    /**
     * @param $customerId
     * @param $customerEmail
     * @param $listOrUnique
     * @return mixed
     * @throws \Exception
     */
    public function find($customerId)
    {
        return [
            'method' => 'GET',
            'uri' => 'customers/'.$customerId,
            'result' => []
        ];
    }  
    
    
    /**
     * @param $customerId
     * @param $customerEmail
     * @return mixed
     * @throws \Exception
     */
    public function get($customerId = null, $customerEmail = null)
    {
        $params = '';

        if(!Helpers::checkStringIsNull($customerId))
        {
            $params .= '&id='.strtolower($customerId);
        }

        if(!Helpers::checkStringIsNull($customerEmail))
        {
            $params .= '&email='.strtolower($customerEmail);
        }

        return [
            'method' => 'GET',
            'uri' => 'customers/search'.($params == '' ? '' : ('?'.substr($params,1))),
            'result' => []
        ];
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function create($data)
    {
        $customer = new \stdClass();
        $customer->email            = strtolower($data->email);
        $customer->first_name       = $data->first_name;
        $customer->last_name        = $data->last_name;
        $customer->description      = isset($data->internal_code) ? $data->internal_code : '';
        $customer->default_address  = null;
        $customer->default_card     = null;
        $customer->date_registered  = isset($data->created_at) ? Carbon::parse($data->created_at)->toIso8601String() : Carbon::now()->toIso8601String();

        //Customer identification
        $customer->identification  = new \stdClass();
        $customer->identification->type   = Helpers::checkStringIsNull($data->document_type) ? null : strtoupper($data->document_type);
        $customer->identification->number = Helpers::checkStringIsNull($data->document_number) ? null : Helpers::removeMask($data->document_number);

        //Customer address
        $customer->address  = new \stdClass();
        $customer->address->zip_code      = Helpers::checkStringIsNull($data->address->postal_code) ? null : Helpers::removeMask($data->address->postal_code);
        $customer->address->street_name   = $data->address->line_1;
        $customer->address->street_number = Helpers::checkStringIsNull($data->address->line_2) ? null : (int) $data->address->line_2;
        $customer->address->city          = new \stdClass();
        $customer->address->city->name    = $data->address->city;


        //Customer phone
        $customer->phone    = new \stdClass();
        $customer->phone->area_code = !Helpers::checkStringIsNull($data->phone->country_code) ? Helpers::removeMask($data->phone->country_code) : '';
        $customer->phone->number    = Helpers::removeMask($data->phone->area_code).Helpers::removeMask($data->phone->number);

        if(Helpers::checkStringIsNull($customer->phone->area_code))
        {
            $customer->phone->area_code = strtoupper($data->address->country) == 'BR' ? '55' : '';
        }

        if(Helpers::checkStringIsNull($customer->phone->area_code) or Helpers::checkStringIsNull($customer->phone->number))
        {
            unset($customer->phone);
        }

        return [
            'method' => 'POST',
            'uri' => 'customers',
            'result' => $customer
        ];
    }

    /**
     * @param $customerId
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function update($customerId, $data)
    {
        $customer = new \stdClass();
        $customer->first_name       = $data->first_name;
        $customer->last_name        = $data->last_name;
        $customer->description      = isset($data->internal_code) ? $data->internal_code : '';
        $customer->default_address  = null;
        $customer->default_card     = null;
        $customer->date_registered  = isset($data->created_at) ? Carbon::parse($data->created_at)->toIso8601String() : Carbon::now()->toIso8601String();

        //Customer identification
        $customer->identification  = new \stdClass();
        $customer->identification->type   = Helpers::checkStringIsNull($data->document_type) ? null : strtoupper($data->document_type);
        $customer->identification->number = Helpers::checkStringIsNull($data->document_number) ? null : Helpers::removeMask($data->document_number);

        //Customer address
        $customer->address  = new \stdClass();
        $customer->address->zip_code      = Helpers::checkStringIsNull($data->address->postal_code) ? null : Helpers::removeMask($data->address->postal_code);
        $customer->address->street_name   = $data->address->line_1;
        $customer->address->street_number = Helpers::checkStringIsNull($data->address->line_2) ? null : (int) $data->address->line_2;
        $customer->address->city          = new \stdClass();
        $customer->address->city->name    = $data->address->city;


        //Customer phone
        $customer->phone    = new \stdClass();
        $customer->phone->area_code = !Helpers::checkStringIsNull($data->phone->country_code) ? Helpers::removeMask($data->phone->country_code) : '';
        $customer->phone->number    = Helpers::removeMask($data->phone->area_code).Helpers::removeMask($data->phone->number);

        if(Helpers::checkStringIsNull($customer->phone->area_code))
        {
            $customer->phone->area_code = strtoupper($data->address->country) == 'BR' ? '55' : '';
        }

        if(Helpers::checkStringIsNull($customer->phone->area_code) or Helpers::checkStringIsNull($customer->phone->number))
        {
            unset($customer->phone);
        }

        return [
            'method' => 'PUT',
            'uri' => 'customers/'.$customerId,
            'result' => $customer
        ];
    }
}
