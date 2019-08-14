<?php

use PHPUnit\Framework\TestCase;
require 'Calculator.php';


class UsersTest extends TestCase
{

//    public function testUsers()
//    {
//        $stack = [];
//        $this->assertSame(0, count($stack));
//
//        array_push($stack, 'foo');
//        $this->assertSame('foo', $stack[count($stack)-1]);
//        $this->assertSame(1, count($stack));
//
//        $this->assertSame('foo', array_pop($stack));
//        $this->assertSame(0, count($stack));
//    }

    private $calculator;

    protected function setUp()
    {
        $this->calculator = new Calculator();
    }

    protected   function tearDown()
    {
        $this->calculator = NULL;
    }

    public function testAdd()
    {
        $result = $this->calculator->add(1, 2);
        $this->assertEquals(3, $result);
    }
}