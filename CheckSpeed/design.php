<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
CallBack('Foo', 1, 2);
// CallBack(View(Input(50)), 1, 2);
CheckSpeed('F');

function Input($max) {
    $ary = [];
    for($i = 0; $i < $max; $i++) {
        $ary[] = rand(0, 1000);
    }
    return $ary;
}

function View($ary) {
    foreach ($ary as $_ary) {
        Output($_ary);
    }
}

function F() {
    $ary = [1, 2, 3, 4];
    $ary2 = array_reverse($ary);
    for ($i = 0; $i < 40527; $i++) {
    }
    return 1;
}

function Foo($x) {
    print_r($x);
    return $x;
}

function CallBack($func, ...$parameter) {
    var_dump($parameter);
    if (empty($parameter)) {
        echo 'パラメータ無';
    }
}