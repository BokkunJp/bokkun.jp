<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
ErrorConfig::noErrorMode();

$test = 1;
$test2 = 2;
$c = compact('test', 'test2');
debug($c);
$d = ['test', 'test2'];
$info = array('コーヒー', '茶色', 'カフェイン');

debug($c);

list($drink, $color) = $d;
debug($drink. ':'. $color);

fTest(a: 10, a1:200);

function fTest(...$argv)
{
    return $argv;
}

$a = 1;
$b = [];
$c = 'c';

$tests = ['φ' => 1];

foreach ($tests as $k => $v) {
    $$k = $v;
    debug($$k);
}

$a = str_repeat('9', 1000); // 999... (1000桁)
$b = '1';
debug(bcadd($a, $b, 0)); // → 1000桁 + 1 → 1001桁の "1" followed by 1000 zeros