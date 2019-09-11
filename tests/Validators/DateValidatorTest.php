<?php

use PHPUnit\Framework\TestCase;
use App\Validators\DateValidator;

class DateValidatorTest extends TestCase
{
    public function addTrueValueProvider()
    {
        return [
            'Date true' => ['2019-08-28 14:24:19.993157', 'Y-m-d H:i:s.u'],
        ];
    }

    public function addFalseValueProvider()
    {
        return [
            'Date false 1' => ['2019-08-28-14:24:19.993157', 'Y-m-d H:i:s.u'],
            'Date false 2' => ['2019-08-28 14:24:19.99', 'Y-m-d H:i:s.u'],
            'Date false 3' => ['2019-08-28 14:24:19.', 'Y-m-d H:i:s.u'],
            'Date false 4' => ['2019-08-28 14:24:19', 'Y-m-d H:i:s.u'],
            'Date false 5' => ['2019-08-28-14:24:19', 'Y-m-d H:i:s.u'],
            'Date false 6' => ['2019-08-28', 'Y-m-d H:i:s.u'],


        ];
    }

    /**
     * @dataProvider addTrueValueProvider
     */
    public function testTrueDate($value, $format)
    {
        $this->assertTrue(DateValidator::validate($value, [
            'format' => $format,
        ]));
    }

    /**
     * @dataProvider addFalseValueProvider
     */
    public function testFalseDate($value, $format)
    {
        $this->assertFalse(DateValidator::validate($value, [
            'format' => $format,
        ]));
    }
}
