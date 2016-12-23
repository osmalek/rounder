<?php

declare(strict_types=1);

namespace Osmalek\Rounder\digit;

use PHPUnit\Framework\TestCase;
use Osmalek\Rounder\digit\exception\NumberIsNotDigitException;

class DigitTest extends TestCase
{
    /**
     * @test
     * @testdox Can not be
     * @dataProvider invalidDigitsProvider
     * @param int $digit
     */
    public function shouldThrowInvalidDigitException(int $digit)
    {
        $this->expectException(NumberIsNotDigitException::class);
        new Digit($digit);
    }

    /**
     * @test
     * @testdox Can be
     * @dataProvider validDigitsProvider
     * @param int $digit
     */
    public function canBeCreated(int $digit)
    {
        $digitObject = new Digit($digit);
        $this->assertEquals($digit, $digitObject->getValue());
    }

    public function invalidDigitsProvider()
    {
        return [
            'negative integer' => [-1],
            'integer greater than 9 ' => [10],
        ];
    }

    public function validDigitsProvider()
    {
        return [
            '0' => [0],
            '1' => [1],
            '2' => [2],
            '3' => [3],
            '4' => [4],
            '5' => [5],
            '6' => [6],
            '7' => [7],
            '8' => [8],
            '9' => [9],
        ];
    }
}
