<?php

use App\Helpers\ArrayHelper;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    public function trueValueUnnestProvider()
    {
        return [
            'unnest true 1' => [
                ['id_1' => '1', 'id_2' => '2'],
                ['id' => ['id_1' => '1', 'id_2' => '2']],
            ],
            'unnest true 2' => [
                ['id_1' => '1'],
                ['id_1' => '1'],
            ],
            'unnest true 3' => [
                ['id_1' => '1', 'id_2' => '2'],
                ['id_1' => '1', 'id_2' => '2'],
            ],
        ];
    }

    public function falseValueUnnestProvider()
    {
        return [
            'unnest false 1' => [
                ['id_1' => '1', 'id_2' => '2'],
                ['id_a' => ['id_b' => ['id_1' => '1', 'id_2' => '2']]],
            ],
            'unnest false 2' => [
                ['id_1' => '1'],
                ['id_1' => '2'],
            ],
            'unnest false 3' => [
                ['id_1' => '1', 'id_2' => '2'],
                ['id_1' => '2', 'id_2' => '1'],
            ],
            'unnest false 4' => [
                ['id_1' => '1', 'id_2' => '2'],
                [],
            ],
        ];
    }

    /**
     * @dataProvider trueValueUnnestProvider
     */
    public function testTrueUnnest($arrCmp, $arr)
    {
        $this->assertEquals($arrCmp, ArrayHelper::unnest($arr));
    }

    /**
     * @dataProvider falseValueUnnestProvider
     */
    public function testFalseUnnest($arrCmp, $arr)
    {
        $this->assertNotEquals($arrCmp, ArrayHelper::unnest($arr));
    }

    public function trueValueArrayDiffKeysProvider()
    {
        return [
            'arrayDiffKeys true 1' => [
                [],
                ['id_1' => 'a', 'id_2' => 'b'],
                ['id_1' => 'c', 'id_2' => 'd'],
            ],
            'arrayDiffKeys true 2' => [
                [],
                ['a' => '1', 'b' => '2'],
                ['b' => '3', 'a' => '4'],
            ],
            'arrayDiffKeys true 3' => [
                ['1' => 'b'],
                ['a' => '1', 'b' => '2'],
                ['c' => '3', 'a' => '4'],
            ],
            'arrayDiffKeys true 4' => [
                ['2' => 'c'],
                ['a' => '123', 'b' => 'abc', 'c' => 'xyz'],
                ['a' => 'qwerty', 'b' => 'xyz', 'd' => 'abc'],
            ],
        ];
    }

    public function falseValueArrayDiffKeysProvider()
    {
        return [
            'arrayDiffKeys false 1' => [
                [],
                ['a' => '1', 'b' => '2'],
                ['c' => '3', 'a' => '4'],
            ],
            'arrayDiffKeys false 2' => [
                [],
                ['a' => '1', 'b' => '2'],
                ['c' => '3', 'c' => '4'],
            ],
            'arrayDiffKeys false 3' => [
                ['1' => 'c'],
                ['a' => '123', 'b' => 'abc', 'c' => 'xyz'],
                ['a' => 'qwerty', 'b' => 'xyz', 'd' => 'abc'],
            ],
        ];
    }

    /**
     * @dataProvider trueValueArrayDiffKeysProvider
     */
    public function testTrueArrayDiffKeys($cmp, $arr1, $arr2)
    {
        $this->assertEquals($cmp, ArrayHelper::arrayDiffKeys($arr1, $arr2));
    }

    /**
     * @dataProvider falseValueArrayDiffKeysProvider
     */
    public function testFalseArrayDiffKeys($cmp, $arr1, $arr2)
    {
        $this->assertNotEquals($cmp, ArrayHelper::arrayDiffKeys($arr1, $arr2));
    }

    public function trueValueGetProvider()
    {
        return [
            'Get true 1' => [
                [
                    'login',
                    'age',
                ],
                [
                    'users' => ['login', 'age',],
                    'comments' => ['users_id',],
                    'likes' => ['comments_id',],
                ],
                [
                    'key' => 'users'
                ],
            ],
            'Get true 2' => [
                null,
                [
                    'users' => ['login', 'age',],
                    'comments' => ['users_id',],
                    'likes' => ['comments_id',],
                ],
                [
                    'key' => 'bad_key'
                ],
            ],
        ];
    }

    public function falseValueGetProvider()
    {
        return [
            'Get false 1' => [
                [
                    'login',
                ],
                [
                    'users' => ['login', 'age',],
                    'comments' => ['users_id',],
                    'likes' => ['comments_id',],
                ],
                [
                    'key' => 'users'
                ],
            ],
            'Get false 2' => [
                [
                    'comments_id',
                    ''
                ],
                [
                    'users' => ['login', 'age',],
                    'comments' => ['users_id',],
                    'likes' => ['comments_id',],
                ],
                [
                    'key' => 'likes'
                ],
            ],
        ];
    }

    /**
     * @dataProvider trueValueGetProvider
     */
    public function testTrueGet($cmp, $array, $key)
    {
        $this->assertEquals($cmp, ArrayHelper::get($array, $key['key']));
    }

    /**
     * @dataProvider falseValueGetProvider
     */
    public function testFalseGet($cmp, $array, $key)
    {
        $this->assertNotEquals($cmp, ArrayHelper::get($array, $key['key']));
    }
}
