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

function Output($expression,$formatFlg=false, $indentFlg=true)
{
    if ($formatFlg === true) {
        print_r("<pre>");
        print_r($expression);
        print_r("</pre>");

    } else {
        print_r($expression);
        if ($indentFlg === true) {
            print_r(nl2br("\n"));
        }
    }
}
