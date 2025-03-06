<?php

namespace Meanify\LaravelPaymentHub\Interfaces;

interface ModelCardInterface
{
    public function get($customerId);

    public function create($customerId, $data);

    public function update($customerId, $cardId, $data);

    public function delete($customerId, $cardId);
}
