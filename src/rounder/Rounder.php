<?php

declare(strict_types=1);

namespace Osmalek\Rounder\rounder;

interface Rounder
{
    public function round(float $floatNumber): float;
}
