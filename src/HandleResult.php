<?php

namespace MindApps\LaravelPayUnity;

use MindApps\LaravelPayUnity\Utils\Helpers;

trait HandleResult
{
    public function format($result)
    {
        //TODO: Develop a attribute mapper that converts the result, regardless of the gateway, into common variables with standardized naming

        return $result;
    }
}
