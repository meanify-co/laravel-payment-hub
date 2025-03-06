<?php

namespace Meanify\LaravelPaymentHub\Interfaces;

interface ModelCustomerInterface
{
    public function find($customerId);
    
    public function get($customerId = null, $customerEmail = null);

    public function create($data);

    public function update($customerId, $data);
}
