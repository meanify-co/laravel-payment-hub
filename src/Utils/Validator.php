<?php

namespace Meanify\LaravelPaymentHub\Utils;

use Meanify\LaravelPaymentHub\Constants;

class Validator
{

    /**
     * @param $gatewayActive
     * @return string
     * @throws \Exception
     */
    public static function gatewayActive($gatewayActive)
    {
        if(!array_key_exists($gatewayActive, Constants::$VALID_GATEWAYS))
        {
            throw new \Exception("Gateway $gatewayActive is not valid");
        }

        return Constants::$VALID_GATEWAYS[$gatewayActive];
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
     * @param $class
     * @param $functionName
     * @param $currentInstanceProperties
     * @return bool
     */
    public static function checkIfNonInterfaceFunctionIsActiveForGateway($class, $functionName, $currentInstanceProperties): bool
    {
        $classParts  = explode('\\', $class);

        $className   = array_pop($classParts);

        $functionKey = $className.'::'.$functionName;

        if(!array_key_exists($functionKey, Constants::$NON_INTERFACE_FUNCTIONS_FOR_GATEWAYS))
        {
            throw new \Exception("Function $functionKey is not valid");
        }
        else
        {
            $gatewayNameAndVersion = $currentInstanceProperties['gatewayActiveName'].'@'.$currentInstanceProperties['gatewayVersion'];

            if(!in_array($gatewayNameAndVersion, Constants::$NON_INTERFACE_FUNCTIONS_FOR_GATEWAYS[$functionKey]))
            {
                return false;
            }
            else
            {
                return true;
            }
        }
    }


    /**
     * @param $data
     * @param $gateway
     * @return array
     */
    public static function customerData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
            {
                $rules = [
                    'email'                  => 'required|email',
                    'first_name'             => 'nullable|minlength:2|maxlength:64',
                    'last_name'              => 'nullable|minlength:2|maxlength:64',
                    'birth_date'             => 'nullable|date:Y-m-d',
                    'document_type'          => 'nullable|expected:cnpj,cpf',
                    'document_number'        => 'nullable|regex:cnpj,cpf',
                    'internal_code'          => 'nullable',
                    'address.line_1'         => 'nullable',
                    'address.line_2'         => 'nullable',
                    'address.postal_code'    => 'nullable|regex:postal_code!'.(isset($data->address->country_code) ? $data->address->country_code : ''),
                    'address.city'           => 'nullable',
                    'address.state_code'     => 'nullable|regex:state_code!'.(isset($data->address->country_code) ? $data->address->country_code : ''),
                    'address.country'        => 'nullable|length:2|regex:country_code',
                    'phone.country_code'     => 'nullable|regex:phone_country_code!'.(isset($data->address_country_code) ? $data->address->country_code : ''),
                    'phone.area_code'        => 'nullable',
                    'phone.number'           => 'nullable',
                ];
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
            {
                $rules = [
                    'first_name'             => 'required|minlength:2|maxlength:64',
                    'last_name'              => 'required|minlength:2|maxlength:64',
                    'email'                  => 'required|email',
                    'birth_date'             => 'nullable|date:Y-m-d',
                    'document_type'          => 'nullable|expected:cnpj,cpf,passport,international',
                    'document_number'        => 'nullable'.(isset($data->document_type) ? (in_array($data->document_type,['cnpj','cpf']) ? '|regex:cnpj,cpf' : '') : ''),
                    'internal_code'          => 'nullable',
                ];

                if(isset($data->address))
                {
                    $rules = array_merge($rules, [
                        'address.street'         => 'nullable',
                        'address.street_number'  => 'nullable',
                        'address.neighborhood'   => 'nullable',
                        'address.complement'     => 'nullable',
                        'address.zipcode'        => 'nullable|regex:zipcode!'.(isset($data->address->country_code) ? $data->address->country_code : ''),
                        'address.city'           => 'nullable',
                        'address.state_code'     => 'nullable|regex:state_code!'.(isset($data->address->country_code) ? $data->address->country_code : ''),
                        'address.country_code'   => 'nullable|length:2|regex:country_code',
                    ]);
                }

                if(isset($data->phone))
                {
                    $rules = array_merge($rules, [
                        'phone.country_code'     => 'nullable|regex:phone_country_code!'.(isset($data->address_country_code) ? $data->address->country_code : ''),
                        'phone.area_code'        => 'required',
                        'phone.number'           => 'required',
                    ]);
                }
            }

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
     * @param $gateway
     * @return array
     */
    public static function cardData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
            {
                $rules = [
                    'number'                    => 'required|minlength:13|maxlength:19',
                    'holder_name'               => 'required|minlength:6|maxlength:64',
                    'holder_document_type'      => 'required|expected:cnpj,cpf',
                    'holder_document_number'    => 'required|regex:cnpj,cpf',
                    'expiration_date'           => 'required|date:Y-m',
                    'cvv'                       => 'required|minlength:3|maxlength:4',
                    'brand'                     => 'required|minlength:3|maxlength:64',
                    'card_type'                 => 'required|expected:credit_card,debit_card',
                ];
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
            {
                $rules = [
                    'number'                         => 'required|minlength:13|maxlength:19',
                    'holder_name'                    => 'required|minlength:6|maxlength:64',
                    'expiration_date'                => 'required|date:Y-m',
                    'cvv'                            => 'required|minlength:3|maxlength:4',
                ];

                if(isset($data->billing_address))
                {
                    $rules = array_merge($rules, [
                        'billing_address.street'         => 'required',
                        'billing_address.street_number'  => 'required',
                        'billing_address.neighborhood'   => 'required',
                        'billing_address.complement'     => 'nullable',
                        'billing_address.zipcode'        => 'required|regex:zipcode!'.(isset($data->billing_address->country_code) ? $data->billing_address->country_code : ''),
                        'billing_address.city'           => 'required',
                        'billing_address.state_code'     => 'required|regex:state_code!'.(isset($data->billing_address->country_code) ? $data->billing_address->country_code : ''),
                        'billing_address.country_code'   => 'required|length:2|regex:country_code',
                    ]);
                }
            }
            

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
     * @param $gateway
     * @return array
     */
    public static function planData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
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
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
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
            }
            
            

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
     * @param $gateway
     * @return array
     */
    public static function subscriptionData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
            {
                $rules = [
                    'internal_code'         => 'nullable',
                    'gateway_customer_id'   => 'required',
                    'gateway_plan_id'       => 'required',
                    'gateway_card_id'       => 'required',
                ];
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
            {
                $rules = [
                    'internal_code'         => 'nullable',
                    'gateway_customer_id'   => 'required',
                    'gateway_plan_id'       => 'required',
                    'gateway_card_id'       => 'required',
                ];
            }

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
     * @param $gateway
     * @return array
     */
    public static function subscriptionCreditCardData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
            {
                $rules = [
                    'gateway_card_id' => 'required',
                ];
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
            {
                $rules = [
                    'gateway_card_id' => 'required',
                ];
            }
            

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
     * @param $gateway
     * @return array
     */
    public static function subscriptionMetadataData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
            {
                $rules = [
                    'metadata' => 'required|array',
                ];
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
            {

                $rules = [
                    'metadata' => 'required|array',
                ];
            }
            

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
     * @param $gateway
     * @return array
     */
    public static function paymentCreditCardData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
            {
                $rules = [
                    'internal_code'                  => 'required',
                    'binary_mode'                    => 'nullable|boolean',
                    'description'                    => 'nullable',
                    'statement_descriptor'           => 'nullable|minlength:3|maxlength:20',
                    'gateway_customer_id'            => 'required',
                    'gateway_customer_email'         => 'required|email',
                    'amount'                         => 'required|decimal:10,2',
                    'installments'                   => 'required|integer',
                    'metadata'                       => 'array',
                    'gateway_card_id'                => 'required',
                    'webhook'                        => 'nullable|url',
                ];
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
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

                ];

                if(isset($data->billing_address))
                {
                    $rules = array_merge($rules, [
                        'billing_address.street'         => 'required_if:gateway_card_id,null',
                        'billing_address.street_number'  => 'required_if:gateway_card_id,null',
                        'billing_address.neighborhood'   => 'required_if:gateway_card_id,null',
                        'billing_address.complement'     => 'nullable',
                        'billing_address.zipcode'        => 'required_if:gateway_card_id,null|regex:zipcode!'.(isset($data->billing_address->country_code) ? $data->billing_address->country_code : ''),
                        'billing_address.city'           => 'required_if:gateway_card_id,null',
                        'billing_address.state_code'     => 'required_if:gateway_card_id,null|regex:state_code!'.(isset($data->billing_address->country_code) ? $data->billing_address->country_code : ''),
                        'billing_address.country_code'   => 'required_if:gateway_card_id,null|length:2|regex:country_code',
                    ]);
                }
            }
            

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
     * @param $gateway
     * @return array
     */
    public static function paymentDebitCardData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
            {
                //
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
            {
                //
            }

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
     * @param $gateway
     * @return array
     */
    public static function paymentPixData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
            {
                $rules = [
                    'internal_code'         => 'required',
                    'description'           => 'nullable',
                    'gateway_customer_id'   => 'required',
                    'amount'                => 'required|decimal:10,2',
                    'pix_expiration_hours'  => 'required|integer|min:4',
                    'metadata'              => 'array',
                ];
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
            {
                $rules = [
                    'internal_code'         => 'required',
                    'description'           => 'nullable',
                    'gateway_customer_id'   => 'required',
                    'amount'                => 'required|decimal:10,2',
                    'pix_expiration_hours'  => 'required|integer|min:4',
                    'metadata'              => 'array',
                ];
            }
            
            
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
     * @param $gateway
     * @return array
     */
    public static function paymentBankSlipData($data, $gateway = null)
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
            $rules = [];

            if($gateway == Constants::$MERCADO_PAGO_GATEWAY_NAME)
            {
                //
            }
            elseif($gateway == Constants::$PAGARME_GATEWAY_NAME)
            {
                //
            }

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
