<?php

namespace MindApps\LaravelPayUnity\Interfaces;

interface ModelPlanInterface
{
    public function get($planId = null);

    public function create($data);

    public function update($planId, $data);

    public function delete($planId);
}
