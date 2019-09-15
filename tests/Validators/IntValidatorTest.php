<?php

use PHPUnit\Framework\TestCase;
use App\Validators\IntValidator;

class IntValidatorTest extends TestCase
{
    public function trueValueProvider()
    {
        return [
            'only digits in string' => ['123'],
            'only digits' => [123],
            'zero' => [0],
            'zero in string' => ['0']
        ];
    }

    public function falseValueProvider()
    {
        return [
            'chars & digits in string' => ['test123'],
            'some symbol' => ['-=@A'],
            'zero and char' => ['0a123'],
        ];
    }

    /**
     * @dataProvider trueValueProvider()
     */
    public function testIsDigit($value)
    {
        $this->assertTrue(IntValidator::validate($value));
    }

    /**
     * @dataProvider falseValueProvider
     */
    public function testIsString($value)
    {
        $this->assertFalse(IntValidator::validate($value));
    }
}
