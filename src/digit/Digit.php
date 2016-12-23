<?php

declare(strict_types=1);

namespace Osmalek\Rounder\digit;

use Osmalek\Rounder\digit\exception\NumberIsNotDigitException;

class Digit
{
    /** @var int */
    private $value;

    public function __construct(int $value)
    {
        if (filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 9]]) === false) {
            throw new NumberIsNotDigitException();
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
