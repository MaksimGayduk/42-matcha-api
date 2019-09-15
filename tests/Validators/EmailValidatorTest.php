<?php

use PHPUnit\Framework\TestCase;
use App\Validators\EmailValidator;

class EmailValidatorTest extends TestCase
{
    public function trueValueProvider()
    {
        return [
            'Email true 1' => ['aleksey@gmail.com'],
            'Email true 2' => ['aleksey#@gmail.com'],
            'Email true 3' => ['Aleksey_123@gmail.com'],
        ];
    }

    public function falseValueProvider()
    {
        return [
            'Email false 1' => ['alekseygmail.com'],
            'Email false 2' => ['aleksey@gmail..com'],
            'Email false 3' => ['aleksey@gmailcom'],
            'Email false 4' => ['aleksey@gmail.com.'],
            'Email false 5' => ['aleksey @gmail.com'],
            'Email false 6' => ['ale@ksey@gmail.com'],
            'Email false 7' => ['aleksey@@mail.com'],
        ];
    }

    /**
     * @dataProvider trueValueProvider
     */
    public function testTrueEmail($value)
    {
        $this->assertTrue(EmailValidator::validate($value));
    }

    /**
     * @dataProvider falseValueProvider
     */
    public function testFalseEmail($value)
    {
        $this->assertFalse(EmailValidator::validate($value));
    }
}
