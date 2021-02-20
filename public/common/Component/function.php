<?php
// 10進数の数値をn進数に変換する
function Convert($decData, $n) {
    if (!isset($n)) {
        var_dump('null');
        return null;
    }
    $octData = 0;
    while (1) {
        if ($decData >= $n) {
            $octData += 10;
            $decData -= $n;
        } else {
            $octData += $decData;
         break;
        }
    }
    return $octData;
}

/**
 * CheckSpeed
 *
 *
 * @param [mixed] $func
 * @return void
 */
function CheckSpeed($func)
{
    $sTime = microtime(true);
    $func();
    $time = microtime(true) - $sTime;
    echo sprintf("%.53f", $time) . nl2br("\n");
}