<?php

namespace Meanify\LaravelPaymentHub\Interfaces;

interface ModelPostbackInterface
{
    public function handle($data);

    public function getRefuseReasonByCode(string $code);
}
