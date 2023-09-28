<?php

namespace MindApps\LaravelPayUnity\Gateways\v5\Pagarme\Models;

use MindApps\LaravelPayUnity\Interfaces\ModelPlanInterface;
use MindApps\LaravelPayUnity\Utils\Helpers;

class Plan implements ModelPlanInterface
{
    /**
     * @param $planId
     * @return array
     */
    public function get($planId = null)
    {
        $result = [];

        return [
            'method' => 'GET',
            'uri' => 'plans/'.$planId,
            'result' => $result
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public function create($data)
    {
        $plan = new \stdClass();
        $plan->name                  = $data->name;
        $plan->currency              = $data->currency;
        $plan->interval              = $data->interval_type;
        $plan->interval_count        = $data->interval_count;
        $plan->billing_type          = $data->billing_type;
        $plan->minimum_price         = Helpers::removeMask($data->amount);
        $plan->payment_methods       = [$data->payment_method];
        $plan->trial_period_days     = isset($data->trial_period_days) ? $data->trial_period_days : null;
        $plan->description           = isset($data->description) ? $data->description : '';
        $plan->statement_descriptor  = isset($data->statement_descriptor) ? substr($data->statement_descriptor,0,13) : '';

        $items = [];
        $item  = new \stdClass();
        $item->name        = $plan->name;
        $item->description = $plan->name;
        $item->quantity    = 1;

        $price = new \stdClass();
        $price->price = $plan->minimum_price;
        $item->pricing_scheme = $price;

        $items[] = $item;
        $plan->items = $items;

        return [
            'method' => 'POST',
            'uri' => 'plans',
            'result' => $plan
        ];
    }

    /**
     * @param $planId
     * @param $data
     * @return array
     */
    public function update($planId, $data)
    {
        $plan = new \stdClass();
        $plan->name                  = $data->name;
        $plan->currency              = $data->currency;
        $plan->interval              = $data->interval_type;
        $plan->interval_count        = $data->interval_count;
        $plan->billing_type          = $data->billing_type;
        $plan->minimum_price         = Helpers::removeMask($data->amount);
        $plan->payment_methods       = [$data->payment_method];
        $plan->trial_period_days     = isset($data->trial_period_days) ? $data->trial_period_days : null;
        $plan->description           = isset($data->description) ? $data->description : '';
        $plan->statement_descriptor  = isset($data->statement_descriptor) ? substr($data->statement_descriptor,0,13) : '';
        $plan->status                = 'active';

        $items = [];
        $item  = new \stdClass();
        $item->name        = $plan->name;
        $item->description = $plan->name;
        $item->quantity    = 1;

        $price = new \stdClass();
        $price->price = $plan->minimum_price;
        $item->pricing_scheme = $price;

        $items[] = $item;
        $plan->items = $items;

        return [
            'method' => 'PUT',
            'uri' => 'plans/'.$planId,
            'result' => $plan
        ];
    }

    /**
     * @param $planId
     * @return array
     */
    public function delete($planId)
    {
        $result = [];

        return [
            'method' => 'DELETE',
            'uri' => 'plans/'.$planId,
            'result' => $result
        ];
    }
}
