<?php

use PHPUnit\Framework\TestCase;
use App\Validators\IntValidator;

class IntValidatorTest extends TestCase
{
    public function addTrueValueProvider()
    {
        return [
            'only digits in string' => ['123'],
            'only digits' => [123],
            'zero' => [0],
            'zero in string' => ['0']
        ];
    }

    public function addFalseValueProvider()
    {
        return [
            'chars & digits in string' => ['test123'],
            'some symbol' => ['-=@A'],
            'zero and char' => ['0a123'],
        ];
    }

    /**
     * @dataProvider addTrueValueProvider()
     */
    public function testIsDigit($value)
    {
        $this->assertTrue(IntValidator::validate($value));
    }

    /**
     * @dataProvider addFalseValueProvider
     */
    public function testIsString($value)
    {
        $this->assertFalse(IntValidator::validate($value));
    }
}
