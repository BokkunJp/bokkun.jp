<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
// 値渡しと参照渡しの実装、速度比較
$x = 1;
$y = 1;
output("before x = ". $x);
foo($x);
output("not return x = ". $x);
$x = foo($x);
output("after x = ". $x);
output("before y = ". $y);
bar($y);
output("after y = ". $y);

// 20000個の整数配列x100のインクリメントをしたときのテスト
$fooTime = checkSpeed('Foo', input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000));
$barTime = checkSpeed('Bar', input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000), input(20000));

output(bcdiv($fooTime, pow(10, 9), 7));      // Fooの実測時間
output(bcdiv($barTime, pow(10, 9), 7));      // Barの実測時間

// 速度比較
if ($fooTime > $barTime) {
    output("barの方が". bcdiv($fooTime - $barTime, pow(10, 9), 7). "秒早い");
} elseif ($barTime > $fooTime) {
    output("fooの方が". bcdiv($barTime - $fooTime, pow(10, 9), 7). "秒早い");
} else {
    output("同じ時間帯");
}

/**
 * input
 *
 * @param integer $max

 * @return array
 */
function input(int $max): array
{
    $ary = [];
    for ($i = 0; $i < $max; $i++) {
        $ary[] = rand(0, 1000);
    }
    return $ary;
}

/**
 * view
 *
 * @param array $ary
 *
 * @return void
 */
function view(array $ary)
{
    foreach ($ary as $_ary) {
        output($_ary);
    }
}

/**
 * foo
 *
 * @param array $x

 * @return array
 */
function foo(array|int $x)
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
 * bar
 *
 * @param array &$x

 * @return void
 */
function bar(array|int &$x)
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
