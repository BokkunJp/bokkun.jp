<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// 値渡しと参照渡しの実装、速度比較
$x = 1;
$y = 1;
Output("before x = ". $x);
Foo($x);
Output("not return x = ". $x);
$x = Foo($x);
Output("after x = ". $x);
Output("before y = ". $y);
Bar($y);
Output("after y = ". $y);

// 20000個の整数配列x100のインクリメントをしたときのテスト
$fooTime = CheckSpeed('Foo', Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000));
$barTime = CheckSpeed('Bar', Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000), Input(20000));

Output(bcdiv($fooTime, pow(10, 9), 7));      // Fooの実測時間
Output(bcdiv($barTime, pow(10, 9), 7));      // Barの実測時間

// 速度比較
if ($fooTime > $barTime) {
    Output("barの方が". bcdiv($fooTime - $barTime, pow(10, 9), 7). "秒早い");
} elseif ($barTime > $fooTime) {
    Output("fooの方が". bcdiv($barTime - $fooTime, pow(10, 9), 7). "秒早い");
} else {
    Output("同じ時間帯");
}

/**
 * Input
 *
 * @param int $max

 * @return array
 */
function Input(int $max): array
{
    $ary = [];
    for ($i = 0; $i < $max; $i++) {
        $ary[] = rand(0, 1000);
    }
    return $ary;
}

/**
 * View
 *
 * @param array $ary
 *
 * @return void
 */
function View(array $ary)
{
    foreach ($ary as $_ary) {
        Output($_ary);
    }
}

/**
 * Foo
 *
 * @param array $x

 * @return array
 */
function Foo(array|int $x)
{
    if (is_array($x)) {
        foreach ($x as $_var) {
            if (is_array($_var)) {
                foreach ($_var as $__v) {
                    $__v++;
                }
            } else {
                $_var++;
            }
        }
    } else {
        $x++;
    }

    return $x;
}

/**
 * Bar
 *
 * @param array &$x

 * @return void
 */
function Bar(array|int &$x)
{
    if (is_array($x)) {
        foreach ($x as $_var) {
            if (is_array($_var)) {
                foreach ($_var as $__v) {
                    $__v++;
                }
            } else {
                $_var++;
            }
        }
    } else {
        $x++;
    }
}
