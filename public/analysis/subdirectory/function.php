<?php
function CalcAverage(Array $data) {
    foreach ($data as $_val) {
        $sum += $_val;
   }

   return $sum / count($data);
}

function CalcMedian(Array $data) {
    $count = 0;
    foreach ($data as $_val) {

    }
}

function CalcMode(Array $data) {
    $count = 0;
    foreach ($data as $_val) {

    }
}

function ResultView($average, $median, $mode) {
    echo '平均値：' . $average . nl2br("\n");
    echo '中央値：' . $median . nl2br("\n");
    echo '最頻値：' . $mode . nl2br("\n");

}