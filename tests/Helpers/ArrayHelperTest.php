<?php

use App\Helpers\ArrayHelper;
use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{
    public function addTrueValueUnnestProvider()
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

    public function addFalseValueUnnestProvider()
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
     * @dataProvider addTrueValueUnnestProvider
     */
    public function testTrueUnnest($arrCmp, $arr)
    {
        $this->assertEquals($arrCmp, ArrayHelper::unnest($arr));
    }

    /**
     * @dataProvider addFalseValueUnnestProvider
     */
    public function testFalseUnnest($arrCmp, $arr)
    {
        $this->assertNotEquals($arrCmp, ArrayHelper::unnest($arr));
    }

    public function addTrueValueArrayDiffKeysProvider()
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

    public function addFalseValueArrayDiffKeysProvider()
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
     * @dataProvider addTrueValueArrayDiffKeysProvider
     */
    public function testTrueArrayDiffKeys($cmp, $arr1, $arr2)
    {
        $this->assertEquals($cmp, ArrayHelper::arrayDiffKeys($arr1, $arr2));
    }

    /**
     * @dataProvider addFalseValueArrayDiffKeysProvider
     */
    public function testFalseArrayDiffKeys($cmp, $arr1, $arr2)
    {
        $this->assertNotEquals($cmp, ArrayHelper::arrayDiffKeys($arr1, $arr2));
    }

    public function addTrueValueGetProvider()
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

    public function addFalseValueGetProvider()
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
     * @dataProvider addTrueValueGetProvider
     */
    public function testTrueGet($cmp, $array, $key)
    {
        $this->assertEquals($cmp, ArrayHelper::get($array, $key['key']));
    }

    /**
     * @dataProvider addFalseValueGetProvider
     */
    public function testFalseGet($cmp, $array, $key)
    {
        $this->assertNotEquals($cmp, ArrayHelper::get($array, $key['key']));
    }
}
