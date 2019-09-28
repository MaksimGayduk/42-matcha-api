<?php

namespace App\Helpers;

class ArrayHelper
{
    public static function get(array $arr, string $key)
    {
        if (!key_exists($key, $arr)) {
            return null;
        }

        return $arr[$key];
    }

    public static function unnest(array $arr): array
    {
        $res = [];

        foreach ($arr as $key => $value) {
            if(is_array($value)) {
                foreach ($value as $k => $v) {
                    $res[$k] = $v;
                }
            } else {
                $res[$key] = $value;
            }
        }

        return $res;
    }

    public static function arrayDiffKeys(array $arr1, array $arr2): array
    {
       return (array_diff(array_keys($arr1), array_keys($arr2)));
    }
}
