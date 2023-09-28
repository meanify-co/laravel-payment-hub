<?php

namespace MindApps\LaravelPayUnity\Utils;

class Helpers
{
    public static $namespacePrefix = '\MindApps\LaravelPayUnity';

    /**
     * @param $gatewayActive
     * @param $version
     * @param $withHandlerFile
     * @return string
     */
    public static function getNamespace($gatewayActive, $version, $withHandlerFile = true)
    {
        $parts = explode('-',$gatewayActive);

        $gatewayActiveName = '';

        foreach($parts as $part)
        {
            $gatewayActiveName .= ucfirst($part);
        }

        return self::$namespacePrefix.'\\Gateways\\'.strtolower($version).'\\'.$gatewayActiveName.($withHandlerFile ? '\\Handler' : '');
    }

    /**
     * @param $data
     * @param $property
     * @param $validationItem
     * @return \stdClass
     */
    public static function formatValidatorArgumentsForTest($data, $property, $validationItem)
    {
        if(Helpers::checkStringContains($property,'.'))
        {
            $parts = explode('.',$property);

            $aux = '$data';

            foreach ($parts as $part)
            {
                $aux .= '->'.$part;
            }

            try
            {
                $value = eval("return $aux;");
            }
            catch (\Throwable $e)
            {
                $value = null;
            }

        }
        else
        {
            $value = isset($data->{$property}) ? $data->{$property} : null;
        }

        $rule  = explode(':',$validationItem);

        $ruleName = Helpers::convertValidatorRuleName($rule[0]);

        $args = new \stdClass();
        $args->ruleName      = $ruleName;
        $args->propertyValue = $value;
        $args->extraParams   = isset($rule[1]) ? $rule[1] : null;
        return $args;
    }

    /**
     * @param $rule
     * @return string
     */
    public static function convertValidatorRuleName($rule)
    {
        $parts = explode('_',$rule);

        $ruleName = '';

        foreach($parts as $part)
        {
            $ruleName .= ucfirst($part);
        }

        return lcfirst($ruleName);
    }

    /**
     * @param $full
     * @param $parts
     * @return bool
     */
    public static function checkStringContains($full, $parts)
    {
        $has = false;

        if(is_string($parts))
        {
            $array = [$parts];

            $parts = $array;
        }

        foreach($parts as $part)
        {
            if(strpos($full, $part) !== false)
            {
                $has = true;

                break;
            }
        }

        return $has;
    }

    /**
     * @param $string
     * @return bool
     */
    public static function checkStringIsNull($string)
    {
        $isEmpty = false;

        if(is_string($string) or is_null($string))
        {
            if($string == null or trim($string) == '')
            {
                $isEmpty = true;
            }
        }

        return $isEmpty;
    }

    /**
     * @param $string
     * @return array|string|string[]
     */
    public static function removeMask($string)
    {
        $chars = [".","_","/","-","(",")"," ","#","=","+"];

        return str_replace($chars, "", $string);
    }

    /**
     * @param $array
     * @param $includeDotChar
     * @return false|string
     */
    public static function arrayToString($array, $includeDotChar = true)
    {
        $string = "";

        foreach($array as $arrayItem)
        {
            $string .= $arrayItem.", ";
        }

        $string = substr($string,0,-2);

        if($includeDotChar)
        {
            $string .= '.';
        }

        return $string;
    }

    /**
     * @notes The JSON contains the results of a GET request made to the
     *        endpoint https://restcountries.com/v3.1/all on 2023-09-27.
     * @return mixed
     */
    public static function countries()
    {
        $jsonFile = __DIR__.'/restcountries.json';

        $json = file_get_contents($jsonFile);

        return json_decode($json);
    }


}
