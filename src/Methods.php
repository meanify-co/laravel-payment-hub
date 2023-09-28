<?php

namespace MindApps\LaravelPayUnity;

use MindApps\LaravelPayUnity\Models\Card;
use MindApps\LaravelPayUnity\Models\Customer;
use MindApps\LaravelPayUnity\Models\Payment;
use MindApps\LaravelPayUnity\Models\Plan;
use MindApps\LaravelPayUnity\Models\Postback;
use MindApps\LaravelPayUnity\Models\Subscription;

trait Methods
{
    public function customer()
    {
        return new Customer($this->getProperties());
    }

    public function card()
    {
        return new Card($this->getProperties());
    }

    public function payment()
    {
        return new Payment($this->getProperties());
    }

    public function plan()
    {
        return new Plan($this->getProperties());
    }

    public function postback()
    {
        return new Postback($this->getProperties());
    }

    public function subscription()
    {
        return new Subscription($this->getProperties());
    }

}
