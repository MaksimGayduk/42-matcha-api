<?php

use PHPUnit\Framework\TestCase;
use App\Validators\LengthValidator;

class LengthValidatorTest extends TestCase
{
    public function addTrueValueProvider()
    {
        return [
            'S string true' => ['sbcde', 1, 5, ],
            'M string true' => ['sbcdesbcdesbcde', 10, 17, ],
        ];
    }

    public function addFalseValueProvider()
    {
        return [
            'S string false' => ['ab', 3, 5, ],
            'M string false' => ['abcdeabcd', 10, 15, ],
        ];
    }

    /**
     * @dataProvider addTrueValueProvider
     */
    public function testTrueLength($value, $min, $max)
    {
        $this->assertTrue(LengthValidator::validate($value, [
            'min' => $min,
            'max' => $max
        ]));
    }

    /**
     * @dataProvider addFalseValueProvider
     */
    public function testFalseLength($value, $min, $max)
    {
        $this->assertFalse(LengthValidator::validate($value, [
            'min' => $min,
            'max' => $max
        ]));
    }
}
