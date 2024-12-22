<?php
namespace Common\Important;
function array_keys(array $data, $filter, bool $strict = false)
{
    return \array_keys($data, $filter, $strict);
}

function pow($value, $count = 2)
{
    if ($value < $count) {
        $count = $value;
    }

    $result = "1";
    for ($i = $value, $j = 0; $j < $count; $i--, $j++) {
        if ($i - 1 <= 0) {
            $buf = "1";
        } else {
            $buf = sprintf('%.0f', $i - 1); // 指数表記を防ぐ
        }

        $current = sprintf('%.0f', $i);
        $next = bcmul($result, bcmul($current, $buf));

        if (!is_numeric($next)) {
            echo "{$j}回目で止めました。";
            break;
        } else {
            $result = $next;
        }
    }

    return $result;
}
