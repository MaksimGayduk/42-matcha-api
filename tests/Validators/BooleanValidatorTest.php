<?php

use PHPUnit\Framework\TestCase;
use App\Validators\BooleanValidator;

class BooleanValidatorTest extends TestCase
{
    public function trueValueProvider()
    {
        return [
            'true string' => ['1'],
            'false string' => ['0'],
            'true digit' => [1],
            'false digit' => [0],
        ];
    }

    public function falseValueProvider()
    {
        return [
            'int' => [2],
            'string' => ['2'],
            'string true' => ['true'],
            'string false' => [false],
            'empty string ' => [''],
            'negative value' => [-1],
        ];
    }

    /**
     * @dataProvider trueValueProvider
     */
    public function testTrueBoolean($value)
    {
        $this->assertTrue(BooleanValidator::validate($value));
    }

    /**
     * @dataProvider falseValueProvider
     */
    public function testFalseBoolean($value)
    {
        $this->assertFalse(BooleanValidator::validate($value));
    }
}
