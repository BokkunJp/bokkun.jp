<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
output("======================================================");
$pathTest = new Path(new Path('test'));
output("======================================================");
output($pathTest->Add('test2', false));
$pathTest->Add('test2');
$pathTest->Add('test2');
$pathTest->AfterMarshal();
// $pathTest->Add('test3');
$pathTest->AfterMarshal();
output("======================================================");

$test1 = 'aaa';
$test2 = null;

if ($test2 === $test1) {
    echo '1';
} else {
    echo '2';
}
echo "<br/>";
function FindFileName(string $str, bool $existFlg = false)
{
    $result = false;
    if (preg_match("/(.*)\.(.*)/", $str)) {
        $result = true;
    }

    if ($existFlg) {
        if (!is_file($str)) {
            $result = false;
        }
    }

    return $result;
}

function Comp(
    int|string $data,
): void {
    if (gettype($data) === 'integer') {
        output($data * $data);
    } else {
        output($data);
    }
}


Comp(1111);
// 11 * 11 = 121
// 111 * 111 = 12321
// 1111 * 1111 = 1234321
// .
// .
// .
// (10^n+10^(n-1)+10^(n-2)+...10^0) * (10^n+10^(n-1)+10^(n-2)+...10^0)
// = {nΣk=0(10^(n-k))}^2
// =
Comp('a');