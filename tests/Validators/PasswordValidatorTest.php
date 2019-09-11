<?php

use PHPUnit\Framework\TestCase;
use App\Validators\PasswordValidator;

class PasswordValidatorTest extends TestCase
{
    public function addTrueValueProvider()
    {
        return [
            'Password true 1' => ['Aleksey!123'],
            'Password true 2' => ['alekseY#3'],
            'Password true 3' => ['AleKsey@1'],
        ];
    }

    public function addFalseValueProvider()
    {
        return [
            'Password false 1' => ['Aleksey!'],
            'Password false 2' => ['aleksey#123'],
            'Password false 3' => ['ALEKSEY!123'],
            'Password false 4' => ['Aleksey123'],
            'Password false 5' => ['@#!?_1'],
            'Password false 6' => ['ALEKSEY'],
            'Password false 7' => ['aleksey'],
            'Password false 8' => ['1234567890'],
            'Password false 9' => ['1234567890@'],
        ];
    }

    /**
     * @dataProvider addTrueValueProvider
     */
    public function testTrueEmail($value)
    {
        $this->assertTrue(PasswordValidator::validate($value));
    }

    /**
     * @dataProvider addFalseValueProvider
     */
    public function testFalseEmail($value)
    {
        $this->assertFalse(PasswordValidator::validate($value));
    }
}
