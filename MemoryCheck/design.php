<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$t = [1, 1, 1];

output($t[0]);

pointer($t[0]);

output($t[1]);

incriment($t[1]);

$t[3] = returnArray(2, returnArray(1));

output($t[3]);

function incriment(int $test)
{
    return $test++;
}

function pointer(int &$test)
{
    $test++;
}

function returnArray(int $data, array $arr = [])
{
    $arr[] = $data;

    return $arr;
}
