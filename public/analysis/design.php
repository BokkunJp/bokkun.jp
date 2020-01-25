<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$data = [1, 2, 3, 4];

$sum = 0;
$average = $mode = $median = 0;
$modeArray = [];
foreach ($data as $_val) {
    $sum += $_val;
    if (empty($modeArray[$_val])) {
        $modeArray[$_val] = 1;
    } else {
        $modeArray[$_val]++;
    }
}
$average = $sum / count($data);

ResultView($average, $median, $mode);

