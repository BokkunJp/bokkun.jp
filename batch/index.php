<?php
var_dump($argv);die;
if (isset($argv[2])) {
    $limit = $argv[2] * 0.1;
} else {
    $limit = 0.1;
}
$firstTime = new DateTime("now");
while (1) {
    $localTime =new DateTime("now");
    $m = ($localTime->getTimestamp() - $firstTime->getTimestamp()) / 60;



    if ($m >= $limit) {
        break;
    }
}

//echo var_dump($argv);
// print(__FILE__).PHP_EOL;
