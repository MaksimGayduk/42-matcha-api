<?php

use PHPUnit\Framework\TestCase;
use App\Validators\BooleanValidator;

class BooleanValidatorTest extends TestCase
{
    public function addTrueValueProvider()
    {
        return [
            'true string' => ['1'],
            'false string' => ['0'],
            'true digit' => [1],
            'false digit' => [0],
        ];
    }

    public function addFalseValueProvider()
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
     * @dataProvider addTrueValueProvider
     */
    public function testTrueBoolean($value)
    {
        $this->assertTrue(BooleanValidator::validate($value));
    }

    /**
     * @dataProvider addFalseValueProvider
     */
    public function testFalseBoolean($value)
    {
        $this->assertFalse(BooleanValidator::validate($value));
    }
}
