<?php

/**
 * Convert
 *
 * 10進数の数値をn進数に変換する
 *
 * @param integer $decData
 * @param integer $n
 *
 * @return integer|false
 */
function convert(int $decData, int $n): int|false
{
    if (!isset($n)) {
        return false;
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
 * checkSpeed
 *
 * スピードテスト用の関数
 *
 * @param string $func
 * @param mixed ...$parameter
 *
 * @return mixed
 */
function checkSpeed(string $func, mixed ...$parameter): mixed
{
    $sTime = hrtime(true);
    if (isset($parameter)) {
        $func($parameter);
    } else {
        $func();
    }
    $time = hrtime(true) - $sTime;

    return $time;
}
