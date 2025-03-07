<?php

namespace Meanify\LaravelPaymentHub;

use Meanify\LaravelPaymentHub\Models\Card;
use Meanify\LaravelPaymentHub\Models\Customer;
use Meanify\LaravelPaymentHub\Models\Payment;
use Meanify\LaravelPaymentHub\Models\PaymentMethod;
use Meanify\LaravelPaymentHub\Models\Postback;

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

    public function paymentMethod()
    {
        return new PaymentMethod($this->getProperties());
    }

    public function postback()
    {
        return new Postback($this->getProperties());
    }

}
