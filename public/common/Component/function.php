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

function Output($expression,$formatFlg=false, $indentFlg=true, array $debug=[])
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

    if (!empty($debug) && isset($debug['on'])) {
        $debugTrace = debug_backtrace();
        Output($debugTrace, formatFlg: true);

        if (isset($debug['layer']) && is_numeric($debug['layer'])) {
            $layer = $debug['layer'];
        } else {
            $layer = 0;
        }

        if (isset($debug['mode'])) {
            switch ($debug['mode']) {
                case 'file':
                case 'line':
                case 'function':
            }


        }
        echo "<pre>source: " . $debugTrace[$layer]['file'] . "</pre>";
        echo "<pre>line: " . $debugTrace[$layer]['line'] . "</pre>";
        echo "<pre>function: " . $debugTrace[$layer]['function'] . "</pre>";

    }
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