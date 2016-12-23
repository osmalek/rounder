<?php

declare(strict_types=1);

namespace Osmalek\Rounder\rounder;

use PHPUnit\Framework\TestCase;
use Osmalek\Rounder\rounder\exception\AtLeastOneAllowedLastPlaceDigitIsRequiredException;
use Osmalek\Rounder\digit\Digit;

class StandardRounderTest extends TestCase
{
    /**
     * @test
     * @testdox At least one digit has to be allowed on last place
     */
    public function shouldThrowExceptionIfNoneDigitIsAllowedOnLastPlace()
    {
        $this->expectException(AtLeastOneAllowedLastPlaceDigitIsRequiredException::class);
        new StandardRounder(1, []);
    }

    /**
     * @test
     * @dataProvider roundDataProvider
     * @testdox Value should be rounded
     * @param int $precision
     * @param array $allowedLastPlaceDigits
     * @param float $value
     * @param float $roundedValue
     */
    public function shouldCorrectlyRoundGivenValue(
        int $precision,
        array $allowedLastPlaceDigits,
        float $value,
        float $roundedValue)
    {
        $rounder = new StandardRounder($precision, $allowedLastPlaceDigits);
        $this->assertEquals($roundedValue, $rounder->round($value));
    }

    public function roundDataProvider()
    {
        return [
            'according to standard rounding rules when all digits are allowed on last place (rounding up)' => [
                1, $this->createAllowedDigitsArray(1, 2, 3, 4, 5, 6, 7, 8, 9), 11.05, 11.1
            ],
            'according to standard rounding rules when all digits are allowed on last place (rounding down)' => [
                1, $this->createAllowedDigitsArray(1, 2, 3, 4, 5, 6, 7, 8, 9), 11.11, 11.1
            ],
            'according to standard rounding rules when all digits are allowed on last place, even if defined in the reverted order' => [
                1, $this->createAllowedDigitsArray(9, 8, 7, 6, 5, 4, 3, 2, 1), 11.11, 11.1
            ],
            'up to the closest last place digit even if it increases higher level digit' => [
                1, $this->createAllowedDigitsArray(7, 1, 5), 10.91, 11.1
            ],
            'down to the closest last place digit even if it decreases higher level digit' => [
                1, $this->createAllowedDigitsArray(5, 4, 7), 11.04, 10.7
            ],
            'correctly (up) when only one digit is allowed on last place' => [
                1, $this->createAllowedDigitsArray(4), 11.01, 11.4
            ],
            'correctly (down) when only one digit is allowed on last place' => [
                1, $this->createAllowedDigitsArray(4), 11.71, 11.4
            ],
            'up when it is in the same distance from two possible last place digits' => [
                1, $this->createAllowedDigitsArray(3, 7), 12.5, 12.7
            ],
            'up when it is in the same distance from two possible last place digits and digits were defined in the reverted order' => [
                1, $this->createAllowedDigitsArray(8, 3), 12.55, 12.8
            ],
            'correctly (up) when rounding to hundredths' => [
                2, $this->createAllowedDigitsArray(5, 7), 12.666, 12.67
            ],
            'correctly (down) when rounding to hundredths' => [
                2, $this->createAllowedDigitsArray(5, 7), 12.555, 12.55
            ],
            'correctly (up) when rounding to tenths' => [
                1, $this->createAllowedDigitsArray(5, 7), 12.666, 12.7
            ],
            'correctly (down) when rounding to tenths' => [
                1, $this->createAllowedDigitsArray(5, 7), 12.555, 12.5
            ],
            'correctly (up) when rounding to ones' => [
                0, $this->createAllowedDigitsArray(5, 7), 126.666, 127
            ],
            'correctly (down) when rounding to ones' => [
                0, $this->createAllowedDigitsArray(5, 7), 125.555, 125
            ],
            'correctly (up) when rounding to tens' => [
                -1, $this->createAllowedDigitsArray(5, 7), 1266.666, 1270
            ],
            'correctly (down) when rounding to tens' => [
                -1, $this->createAllowedDigitsArray(5, 7), 1255.555, 1250
            ],
            'correctly (up) when rounding to hundreds' => [
                -2, $this->createAllowedDigitsArray(5, 7), 12666.666, 12700
            ],
            'correctly (down) when rounding to hundreds' => [
                -2, $this->createAllowedDigitsArray(5, 7), 12555.555, 12500
            ],
            'correctly (up) when rounding to thousands' => [
                -3, $this->createAllowedDigitsArray(5, 7), 126666.666, 127000
            ],
            'correctly (down) when rounding to thousands' => [
                -3, $this->createAllowedDigitsArray(5, 7), 125555.555, 125000
            ],
            'correctly (up) when rounding to ten thousands' => [
                -4, $this->createAllowedDigitsArray(5, 7), 1266666.666, 1270000
            ],
            'correctly (down) when rounding to ten thousands' => [
                -4, $this->createAllowedDigitsArray(5, 7), 1255555.555, 1250000
            ],
            'correctly (up) when rounding to hundred thousands' => [
                -5, $this->createAllowedDigitsArray(5, 7), 12666666.666, 12700000
            ],
            'correctly (down) when rounding to hundred thousands' => [
                -5, $this->createAllowedDigitsArray(5, 7), 12555555.555, 12500000
            ],
            'correctly (up) when rounding to millions' => [
                -6, $this->createAllowedDigitsArray(5, 7), 126666666.666, 127000000
            ],
            'correctly (down) when rounding to millions' => [
                -6, $this->createAllowedDigitsArray(5, 7), 125555555.555, 125000000
            ],
        ];
    }

    private function createAllowedDigitsArray(int ...$digits)
    {
        $digitsArray = [];
        foreach ($digits as $digit) {
            $digitsArray[] = new Digit($digit);
        }
        return $digitsArray;
    }
}
