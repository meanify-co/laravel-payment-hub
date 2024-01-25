<?php

namespace MindApps\LaravelPayUnity\Gateways\v5\Pagarme\Models;

use Carbon\Carbon;
use MindApps\LaravelPayUnity\Interfaces\ModelCustomerInterface;
use MindApps\LaravelPayUnity\Utils\Helpers;

class Customer implements ModelCustomerInterface
{
    /**
     * @param $customerInternalCode
     * @param $customerEmail
     * @return array
     */
    public function get($customerInternalCode = null, $customerEmail = null)
    {
        $params = '';

        if(!Helpers::checkStringIsNull($customerInternalCode))
        {
            $params .= '&code='.$customerInternalCode;
        }

        if(!Helpers::checkStringIsNull($customerEmail))
        {
            $params .= '&email='.strtolower($customerEmail);
        }

        return [
            'method' => 'GET',
            'uri' => 'customers'.($params == '' ? '' : ('?'.substr($params,1))),
            'result' => []
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public function create($data)
    {
        //format document type
        $data->document_type = strtoupper($data->document_type);
        $data->document_type = $data->document_type == 'INTERNATIONAL' ? 'PASSPORT' : $data->document_type;

        //Customer main data
        $customer = new \stdClass();
        $customer->name          = $data->first_name.' '.$data->last_name;
        $customer->email         = strtolower($data->email);
        $customer->birth_date    = !Helpers::checkStringIsNull($data->birth_date) ? Carbon::createFromFormat('Y-m-d',$data->birth_date)->format('m/d/Y') : null;
        $customer->type          = $data->document_type == 'cnpj' ? 'company' : 'individual';
        $customer->document      = Helpers::removeMask($data->document_number);
        $customer->document_type = $data->document_type;
        $customer->code          = isset($data->internal_code) ? $data->internal_code : '';

        //Customer address
        $customer->address  = new \stdClass();
        $customer->address->line_1   = $data->address->street_number.','.$data->address->street.','.$data->address->neighborhood;;
        $customer->address->line_2   = isset($data->address->complement) ? $data->address->complement : '';
        $customer->address->zip_code = Helpers::removeMask($data->address->zipcode);
        $customer->address->city     = $data->address->city;
        $customer->address->state    = strtoupper($data->address->state_code);
        $customer->address->country  = strtoupper($data->address->country_code);

        //Customer phone
        $customer->phone    = new \stdClass();
        $customer->phone->country_code = !Helpers::checkStringIsNull($data->phone->country_code) ? Helpers::removeMask($data->phone->country_code) : '';
        $customer->phone->area_code    = Helpers::removeMask($data->phone->area_code);
        $customer->phone->number       = Helpers::removeMask($data->phone->number);

        if(Helpers::checkStringIsNull($customer->phone->country_code))
        {
            $customer->phone->country_code = $customer->address->country == 'BR' ? '55' : '';
        }

        if(Helpers::checkStringIsNull($customer->phone->country_code) or Helpers::checkStringIsNull($customer->phone->area_code) or Helpers::checkStringIsNull($customer->phone->number))
        {
            unset($customer->phone);
        }

        if(isset($customer->phone))
        {
            $phones = new \stdClass();
            $phones->home_phone   = clone $customer->phone;
            $phones->mobile_phone = clone $customer->phone;
            $customer->phones = $phones;

            unset($customer->phone);
        }

        return [
            'method' => 'POST',
            'uri' => 'customers',
            'result' => $customer
        ];
    }

    /**
     * @notes The function to edit a customer in Pagar.me is the same as creating a customer.
     *         The customer's email is used as the primary key.
     *         Therefore, when editing a customer with the same email, the data will be updated.
     *         If there are no customers with the corresponding email, a new customer is registered.
     * @param $customerId
     * @param $data
     * @return array
     */
    public function update($customerId, $data)
    {
        return $this->create($data);
    }
}
