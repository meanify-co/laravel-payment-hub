<?php

namespace MindApps\LaravelPayUnity\Utils;

class Validator
{
    /**
     * @var string[]
     */
    public static $validGateways = [
        'mercado-pago' => 'MercadoPago',
        'mercadopago'  => 'MercadoPago',
        'pagarme'      => 'Pagarme',
    ];

    /**
     * @param $gatewayActive
     * @return string
     * @throws \Exception
     */
    public static function gatewayActive($gatewayActive)
    {
        if(!array_key_exists($gatewayActive, self::$validGateways))
        {
            throw new \Exception("Gateway $gatewayActive is not valid");
        }

        return self::$validGateways[$gatewayActive];
    }

    /**
     * @param $gatewayActiveName
     * @param $gatewayVersion
     * @return string
     * @throws \Exception
     */
    public static function gatewayVersion($gatewayActiveName, $gatewayVersion)
    {
        $gatewayVersion = strtolower($gatewayVersion);

        if(trim($gatewayVersion) == '')
        {
            throw new \Exception("Version not sent");
        }
        elseif(!is_dir(__DIR__.'/../Gateways/'.$gatewayVersion.'/'.$gatewayActiveName))
        {
            throw new \Exception("Version $gatewayVersion is not valid");
        }
        elseif(!file_exists(__DIR__.'/../Gateways/'.$gatewayVersion.'/'.$gatewayActiveName.'/Handler.php'))
        {
            throw new \Exception("Handler version $gatewayVersion not found");
        }

        return $gatewayVersion;
    }

    /**
     * @param $gatewayActiveName
     * @param $gatewayVersion
     * @param $gatewayEnvironment
     * @return string
     * @throws \Exception
     */
    public static function gatewayEnvironment($gatewayActiveName, $gatewayVersion, $gatewayEnvironment)
    {
        $gatewayEnvironment = strtolower($gatewayEnvironment);

        $gatewayPath = Helpers::getNamespace($gatewayActiveName, $gatewayVersion);

        if(!in_array($gatewayEnvironment, $gatewayPath::$validEnvironments))
        {
            throw new \Exception("Environment not available for $gatewayActiveName");
        }

        return $gatewayEnvironment;
    }

    /**
     * @param $gatewayActiveName
     * @param $gatewayVersion
     * @param $gatewayParams
     * @return mixed
     * @throws \Exception
     */
    public static function gatewayParams($gatewayActiveName, $gatewayVersion, $gatewayParams)
    {
        $gatewayPath = Helpers::getNamespace($gatewayActiveName, $gatewayVersion);

        foreach($gatewayPath::$requiredParams as $param)
        {
            if(!array_key_exists($param, $gatewayParams))
            {
                throw new \Exception("Param $param not found");
            }
        }

        return $gatewayParams;
    }

    /**
     * @param $data
     * @return array
     */
    public static function customerData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {
            $rules = [
                'first_name'             => 'required|minlength:2|maxlength:64',
                'last_name'              => 'required|minlength:2|maxlength:64',
                'email'                  => 'required|email',
                'birth_date'             => 'nullable|date:Y-m-d',
                'document_type'          => 'required|expected:cnpj,cpf',
                'document_number'        => 'required|regex:cnpj,cpf',
                'internal_code'          => 'nullable',
                'address.street'         => 'required',
                'address.street_number'  => 'required',
                'address.neighborhood'   => 'required',
                'address.complement'     => 'nullable',
                'address.zipcode'        => 'required|regex:zipcode!'.(isset($data->address->country_code) ? $data->address->country_code : ''),
                'address.city'           => 'required',
                'address.state_code'     => 'required|regex:state_code!'.(isset($data->address->country_code) ? $data->address->country_code : ''),
                'address.country_code'   => 'required|length:2|regex:country_code',
                'phone.country_code'     => 'required|regex:phone_country_code!'.(isset($data->address_country_code) ? $data->address->country_code : ''),
                'phone.area_code'        => 'required',
                'phone.number'           => 'required',
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }

    /**
     * @param $data
     * @return array
     */
    public static function cardData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {

            $rules = [
                'card_token'                     => 'nullable',
                'number'                         => 'required_if:card_token,null|minlength:13|maxlength:19',
                'holder_name'                    => 'required_if:card_token,null|minlength:6|maxlength:64',
                'expiration_date'                => 'required_if:card_token,null|date:Y-m',
                'cvv'                            => 'required_if:card_token,null|minlength:3|maxlength:4',
                'billing_address.street'         => 'required_if:card_token,null',
                'billing_address.street_number'  => 'required_if:card_token,null',
                'billing_address.neighborhood'   => 'required_if:card_token,null',
                'billing_address.complement'     => 'nullable',
                'billing_address.zipcode'        => 'required_if:card_token,null|regex:zipcode!'.(isset($data->billing_address->country_code) ? $data->billing_address->country_code : ''),
                'billing_address.city'           => 'required_if:card_token,null',
                'billing_address.state_code'     => 'required_if:card_token,null|regex:state_code!'.(isset($data->billing_address->country_code) ? $data->billing_address->country_code : ''),
                'billing_address.country_code'   => 'required_if:card_token,null|length:2|regex:country_code',
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }

    /**
     * @param $data
     * @return array
     */
    public static function planData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {
            $rules = [
                'name'              => 'required',
                'currency'          => 'required|expected:BRL',
                'amount'            => 'required|decimal:10,2',
                'payment_method'    => 'required|expected:credit_card',
                'billing_type'      => 'required|expected:prepaid',
                'interval_type'     => 'required|expected:day,month,year',
                'interval_count'    => 'required|integer',
                'trial_period_days' => 'integer',
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }

    /**
     * @param $data
     * @return array
     */
    public static function subscriptionData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {
            $rules = [
                'internal_code'         => 'nullable',
                'gateway_customer_id'   => 'required',
                'gateway_plan_id'       => 'required',
                'gateway_card_id'       => 'required',
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }

    /**
     * @param $data
     * @return array
     */
    public static function subscriptionCreditCardData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {
            $rules = [
                'gateway_card_id' => 'required',
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }

    /**
     * @param $data
     * @return array
     */
    public static function subscriptionMetadataData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {
            $rules = [
                'metadata'   => 'required|array',
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }

    /**
     * @param $data
     * @return array
     */
    public static function paymentCreditCardData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {
            $rules = [
                'internal_code'                  => 'required',
                'description'                    => 'nullable',
                'gateway_customer_id'            => 'required',
                'amount'                         => 'required|decimal:10,2',
                'installments'                   => 'required|integer',
                'metadata'                       => 'array',
                'gateway_card_id'                => 'nullable',
                'number'                         => 'required_if:gateway_card_id,null|minlength:13|maxlength:19',
                'holder_name'                    => 'required_if:gateway_card_id,null|minlength:6|maxlength:64',
                'expiration_date'                => 'required_if:gateway_card_id,null|date:Y-m',
                'cvv'                            => 'required_if:gateway_card_id,null|minlength:3|maxlength:4',
                'billing_address.street'         => 'required_if:gateway_card_id,null',
                'billing_address.street_number'  => 'required_if:gateway_card_id,null',
                'billing_address.neighborhood'   => 'required_if:gateway_card_id,null',
                'billing_address.complement'     => 'nullable',
                'billing_address.zipcode'        => 'required_if:gateway_card_id,null|regex:zipcode!'.(isset($data->billing_address->country_code) ? $data->billing_address->country_code : ''),
                'billing_address.city'           => 'required_if:gateway_card_id,null',
                'billing_address.state_code'     => 'required_if:gateway_card_id,null|regex:state_code!'.(isset($data->billing_address->country_code) ? $data->billing_address->country_code : ''),
                'billing_address.country_code'   => 'required_if:gateway_card_id,null|length:2|regex:country_code',
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }

    /**
     * @param $data
     * @return array
     */
    public static function paymentDebitCardData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {
            $rules = [
                //
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }

    /**
     * @param $data
     * @return array
     */
    public static function paymentPixData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {
            $rules = [
                'internal_code'         => 'required',
                'description'           => 'nullable',
                'gateway_customer_id'   => 'required',
                'amount'                => 'required|decimal:10,2',
                'pix_expiration_hours'  => 'required|integer|min:4',
                'metadata'              => 'array',
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }

    /**
     * @param $data
     * @return array
     */
    public static function paymentBankSlipData($data)
    {
        $success = true;
        $errors  = [];

        if(!is_object($data))
        {
            $success  = false;
            $errors[] = 'Data is not stdClass';
        }
        else
        {
            $rules = [
                //
            ];

            foreach($rules as $property => $validation)
            {
                $validations = explode('|',$validation);

                foreach($validations as $validationItem)
                {
                    $args  = Helpers::formatValidatorArgumentsForTest($data, $property, $validationItem);

                    $test  = ValidatorRules::{$args->ruleName}($data, $args->propertyValue, $args->extraParams);

                    if(!$test)
                    {
                        $success  = false;
                        $errors[] = $property.' validation error for rule "'.$validationItem.'"';
                    }
                }
            }
        }

        return ['success' => $success, 'errors' => Helpers::arrayToString($errors)];
    }
}
