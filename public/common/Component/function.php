<?php

/**
 * Convert
 *
 * 10進数の数値をn進数に変換する
 *
 * @param int $decData
 * @param int $n
 * @return int
 */
function Convert(int $decData, int $n): int
{
    if (!isset($n)) {
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
 * スピードテスト用の関数
 *
 * @param string $func
 * @param mixed ...$parameter
 *
 * @return mixed
 */
function CheckSpeed(string $func, mixed ...$parameter): mixed
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
