<?php

declare(strict_types=1);

namespace Osmalek\Rounder\rounder;

use Osmalek\Rounder\digit\Digit;
use Osmalek\Rounder\rounder\exception\AtLeastOneAllowedLastPlaceDigitIsRequiredException;

class StandardRounder implements Rounder
{
    /** @var int */
    private $precision;

    /** @var int */
    private $powerOfTen;

    /** @var Digit[] */
    private $allowedLastPlaceDigits;

    public function __construct(int $precision, array $allowedLastPlaceDigits)
    {
        if (empty($allowedLastPlaceDigits)) {
            throw new AtLeastOneAllowedLastPlaceDigitIsRequiredException();
        }
        $this->precision = $precision;
        $this->powerOfTen = pow(10, -$this->precision);
        $this->allowedLastPlaceDigits = $allowedLastPlaceDigits;
    }

    public function round(float $value): float
    {
        $baseValue = round($value, $this->precision - 1);
        $closestValue = null;
        $minDifference = null;
        foreach ($this->allowedLastPlaceDigits as $digit) {
            $roundedPart = $digit->getValue() * $this->powerOfTen;
            $modifiedValue = $this->getModifiedValue($value, $baseValue, $roundedPart);
            $difference = abs($modifiedValue - $value);
            if (is_null($minDifference)
                || $difference < $minDifference
                || ($difference === $minDifference && $modifiedValue > $closestValue)
            ) {
                $closestValue = $modifiedValue;
                $minDifference = $difference;
            }
        }
        return $closestValue;
    }

    private function getModifiedValue(float $value, float $baseValue, float $roundedPart): float
    {
        if ($baseValue + $roundedPart < ($value + 5 * $this->powerOfTen)) {
            return $baseValue + $roundedPart;
        }
        return ($baseValue - 10 * $this->powerOfTen) + $roundedPart;
    }
}
