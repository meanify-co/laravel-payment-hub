<?php

namespace MindApps\LaravelPayUnity\Interfaces;

interface ModelCustomerInterface
{
    public function get($customerInternalCode = null, $customerEmail = null);

    public function create($data);

    public function update($customerId, $data);
}
