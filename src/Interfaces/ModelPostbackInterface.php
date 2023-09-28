<?php

namespace MindApps\LaravelPayUnity\Interfaces;

interface ModelPostbackInterface
{
    public function handle($data);

    public function getRefuseReasonByCode(string $code);
}
