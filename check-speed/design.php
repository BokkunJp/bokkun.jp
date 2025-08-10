<?php
/**
 * 指定した要素数・サイズの配列を生成する
 *
 * @param int $count       要素数
 * @param int $elementSize 1要素の文字列サイズ（バイト単位）
 * @return array           生成された配列
 */
function generateArray(int $count, int $elementSize): array {
    $arr = [];
    $str = str_repeat('A', $elementSize);
    for ($i = 0; $i < $count; $i++) {
        $arr[$i] = $str;
    }
    return $arr;
}

/**
 * 値渡しで配列を受け取り、要素を変更する関数
 * （コピーが発生するため処理が重い）
 *
 * @param array $data 値渡しで受け取る配列
 * @return void
 */
function foo(array $data): void {
    $data = array_merge([], $data); // 明示的にコピー
    $data[0] = 'X';
}

/**
 * 参照渡しで配列を受け取り、要素を変更する関数
 * （コピーは発生しないため高速）
 *
 * @param array &$data 参照渡しで受け取る配列
 * @return void
 */
function bar(array &$data): void {
    $data[0] = 'X';
}

// テスト条件
$elements = 250000;     // 要素数：25万
$elementSize = 512;     // 1要素のサイズ：1KB
$loops = 300;            // 繰り返し回数：300回

// 同一内容の配列を用意
$arr1 = generateArray($elements, $elementSize);
$arr2 = $arr1;

// foo の計測
$start = microtime(true);
for ($i = 0; $i < $loops; $i++) {
    foo($arr1);
}
$timeFoo = microtime(true) - $start;

// bar の計測
$start = microtime(true);
for ($i = 0; $i < $loops; $i++) {
    bar($arr2);
}
$timeBar = microtime(true) - $start;

// 結果出力
echo "foo: {$timeFoo}秒<br>";
echo "bar: {$timeBar}秒<br>";
echo "差: " . ($timeFoo - $timeBar) . "秒<br>";
