<?php

use PHPUnit\Framework\TestCase;
use App\Validators\LengthValidator;

class LengthValidatorTest extends TestCase
{
    public function trueValueProvider()
    {
        return [
            'S string true' => ['sbcde', 1, 5, ],
            'M string true' => ['sbcdesbcdesbcde', 10, 17, ],
        ];
    }

    public function falseValueProvider()
    {
        return [
            'S string false' => ['ab', 3, 5, ],
            'M string false' => ['abcdeabcd', 10, 15, ],
        ];
    }

    /**
     * @dataProvider trueValueProvider
     */
    public function testTrueLength($value, $min, $max)
    {
        $this->assertTrue(LengthValidator::validate($value, [
            'min' => $min,
            'max' => $max
        ]));
    }

    /**
     * @dataProvider falseValueProvider
     */
    public function testFalseLength($value, $min, $max)
    {
        $this->assertFalse(LengthValidator::validate($value, [
            'min' => $min,
            'max' => $max
        ]));
    }
}
