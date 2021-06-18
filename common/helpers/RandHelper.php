<?php

namespace common\helpers;

use yii;

class RandHelper
{
    // 当前的毫秒时间戳
    public static function msectime()
    {
        $arr = explode(' ', microtime());
        $tmp1 = $arr[0];
        $tmp2 = $arr[1];
        return (float)sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
    }
    // 10进制转62进制
    public static function dec62($dec)
    {
        $base = 62;
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $ret = '';
        for($t = floor(log10($dec) / log10($base)); $t >= 0; $t--){
            $a = floor($dec / pow($base, $t));
            $ret .= substr($chars, $a, 1);
            $dec -= $a * pow($base, $t);
        }
        return $ret;
    }
    // 随机字符
    public static function randChar()
    {
        $base = 62;
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return $chars[mt_rand(1, $base) - 1];
    }
}
