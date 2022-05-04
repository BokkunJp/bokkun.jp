<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$t = [1, 1, 1];

Output($t[0]);

Pointer($t[0]);

Output($t[1]);

Incriment($t[1]);

$t[3] = ReturnArray(2, ReturnArray(1));

Output($t[3]);

function Incriment(int $test)
{
    return $test++;
}

function Pointer(int &$test)
{
    $test++;
}

function ReturnArray(int $data, array $arr = [])
{
    $arr[] = $data;

    return $arr;
}
