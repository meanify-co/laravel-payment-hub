<?php

namespace MindApps\LaravelPayUnity\Gateways\v1\MercadoPago\Models;

use Carbon\Carbon;
use MindApps\LaravelPayUnity\Interfaces\ModelCustomerInterface;
use MindApps\LaravelPayUnity\Utils\Helpers;

class Customer implements ModelCustomerInterface
{
    /**
     * @param $customerInternalCode
     * @param $customerEmail
     * @return mixed
     * @throws \Exception
     */
    public function get($customerInternalCode = null, $customerEmail = null)
    {
        $params = '';

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
        $customer->email = strtolower($data->email);

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
        //Customer main data
        $customer = new \stdClass();
        $customer->first_name       = $data->first_name;
        $customer->last_name        = $data->last_name;
        $customer->description      = isset($data->internal_code) ? $data->internal_code : '';
        $customer->default_address  = null;
        $customer->default_card     = null;
        $customer->date_registered  = isset($data->created_at) ? Carbon::parse($data->created_at)->toIso8601String() : Carbon::now()->toIso8601String();

        //Customer identification
        $customer->identification  = new \stdClass();
        $customer->identification->type   = strtoupper($data->document_type);
        $customer->identification->number = Helpers::removeMask($data->document_number);

        //Customer address
        $customer->address  = new \stdClass();
        $customer->address->zip_code      = Helpers::removeMask($data->address->zipcode);
        $customer->address->street_name   = $data->address->street;
        $customer->address->street_number = (int) $data->address->street_number;
        $customer->address->city          = new \stdClass();
        $customer->address->city->name    = $data->address->city;


        //Customer phone
        $customer->phone    = new \stdClass();
        $customer->phone->area_code = !Helpers::checkStringIsNull($data->phone->country_code) ? Helpers::removeMask($data->phone->country_code) : '';
        $customer->phone->number    = Helpers::removeMask($data->phone->area_code).Helpers::removeMask($data->phone->number);

        if(Helpers::checkStringIsNull($customer->phone->area_code))
        {
            $customer->phone->area_code = strtoupper($data->address->country_code) == 'BR' ? '55' : '';
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
