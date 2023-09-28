<?php

namespace MindApps\LaravelPayUnity\Interfaces;

interface ModelSubscriptionInterface
{
    public function get($subscriptionId = null);

    public function create($data);

    public function updateCreditCard($subscriptionId, $data);

    public function updateMetadata($subscriptionId, $data);

    public function cancel($subscriptionId);

    public function enableManualBilling($subscriptionId);

    public function disableManualBilling($subscriptionId);
}
