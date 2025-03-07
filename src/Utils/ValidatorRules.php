<?php

namespace Meanify\LaravelPaymentHub\Utils;

use Respect\Validation\Validator as respect;

class ValidatorRules
{
    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function nullable($object, $value)
    {
        $success = true;

        //

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function required($object, $value)
    {
        $success = true;

        if(Helpers::checkStringIsNull($value))
        {
            $success = false;
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $conditions
     * @return bool
     */
    public static function requiredIf($object, $value, $conditions)
    {
        $success = true;

        $where = [];

        $conditions = explode(',',$conditions);

        $lastKey = null;

        for($i = 1; $i <= count($conditions); $i++)
        {
            if($i % 2 === 0)
            {
                $where[$lastKey] = (strtolower($conditions[$i - 1]) == 'null') ? null : $conditions[$i - 1];
            }
            else
            {
                $lastKey = $conditions[$i - 1];
                $where[$lastKey] = null;
            }
        }

        $isRequired = true;

        foreach($where as $conditionKey => $conditionValue)
        {
            $objectValue = isset($object->{$conditionKey}) ? $object->{$conditionKey} : null;

            $objectValue = Helpers::checkStringIsNull($objectValue) ? null : $objectValue;

            if($objectValue != $conditionValue)
            {
                $isRequired = false;
            }
        }

        if($isRequired and Helpers::checkStringIsNull($value))
        {
            $success = false;
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function array($object, $value)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            if(!is_array($value))
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function email($object, $value)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            if(!filter_var($value, FILTER_VALIDATE_EMAIL))
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $format
     * @return bool
     */
    public static function date($object, $value, $format)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            try
            {
                $dateTime = \DateTime::createFromFormat($format, $value);

                if ($dateTime !== false && $dateTime->format($format) === $value)
                {
                    //
                }
                else
                {
                    $success = false;
                }
            }
            catch (\Throwable $e)
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $expectedValues
     * @return bool
     */
    public static function expected($object, $value, $expectedValues)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            $expectedValues = explode(',',$expectedValues);

            if(!in_array($value, $expectedValues))
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function integer($object, $value)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            if(!respect::digit()->validate($value))
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function decimal($object, $value, $decimalFormat)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            list($maxIntegerLength, $decimalPlaces) = explode(',',$decimalFormat);

            if(!respect::decimal($decimalPlaces)->validate($value))
            {
                $success = false;
            }
            else
            {
                list($integer, $cents) = explode('.',$value);

                if(strlen((int)$integer) > $maxIntegerLength)
                {
                    $success = false;
                }
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function url($object, $value)
    {
        $success = true;

        if(!respect::url()->validate($value))
        {
            $success = false;
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function boolean($object, $value)
    {
        $success = true;

        if(!is_bool($value))
        {
            $success = false;
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $length
     * @return bool
     */
    public static function length($object, $value, $length)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            if(strlen($value) != $length)
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $minLength
     * @return bool
     */
    public static function minlength($object, $value, $minLength)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            if(strlen($value) < $minLength)
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $maxLength
     * @return bool
     */
    public static function maxlength($object, $value, $maxLength)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            if(strlen($value) > $maxLength)
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $minLength
     * @return bool
     */
    public static function min($object, $value, $minLength)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            if($value < $minLength)
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $maxLength
     * @return bool
     */
    public static function max($object, $value, $maxLength)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            if($value > $maxLength)
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $tests
     * @return bool
     */
    public static function regex($object, $value, $tests)
    {
        $success = true;

        if(!Helpers::checkStringIsNull($value))
        {
            $totalValid = 0;

            $tests = explode(',',$tests);

            foreach($tests as $test)
            {
                $param = null;

                if(Helpers::checkStringContains($test, '!'))
                {
                    list($test, $param) = explode('!',$test);
                }


                $parts = explode('_',$test);

                $testName = '';

                foreach($parts as $part)
                {
                    $testName .= ucfirst($part);
                }


                if(self::{'regex'.$testName}($object, $value, $param))
                {
                    $totalValid++;
                }
            }

            if($totalValid == 0)
            {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function regexCnpj($object, $value)
    {
        $success = true;

        if(!respect::cnpj()->validate($value))
        {
            $success = false;
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function regexCpf($object, $value)
    {
        $success = true;

        if(!respect::cpf()->validate($value))
        {
            $success = false;
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @return bool
     */
    public static function regexCountryCode($object, $value)
    {
        $success = true;

        if(!respect::countryCode('alpha-2')->validate($value))
        {
            $success = false;
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $countryCode
     * @return bool
     */
    public static function regexStateCode($object, $value, $countryCode)
    {
        $success = true;

        try
        {
            if(!respect::subdivisionCode($countryCode)->validate($value))
            {
                $success = false;
            }
        }
        catch (\Throwable $e)
        {
            //error about countryCode. Should be validated in regexCountryCode
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $countryCode
     * @return bool
     */
    public static function regexPostalCode($object, $value, $countryCode)
    {
        $success = true;

        try
        {
            if(!respect::postalCode($countryCode)->validate($value))
            {
                $success = false;
            }
        }
        catch (\Throwable $e)
        {
            //error about countryCode. Should be validated in regexCountryCode
        }

        return $success;
    }

    /**
     * @param $object
     * @param $value
     * @param $countryCode
     * @return bool
     */
    public static function regexPhoneCountryCode($object, $value, $countryCode)
    {
        $success = true;

        try
        {
            $countries   = Helpers::countries();
            $countryData = $countries->{strtoupper($countryCode)};

            $validCodes = [];

            foreach($countryData->idd->suffixes as $suffix)
            {
                $validCodes[] = $countryData->idd->root . $suffix;
                $validCodes[] = str_replace('+','',$countryData->idd->root) . $suffix;
            }

            if(!in_array($value, $validCodes))
            {
                $success = false;
            }
        }
        catch (\Throwable $e)
        {
            //error about countryCode. Should be validated in regexCountryCode
        }

        return $success;
    }
}
