<?php

$ua = new Error\Important\UA();
if ($ua->judgeDevice() === PC_design) {
    echo "<script src='https://code.jquery.com/jquery-3.7.1.min.js' ></script>";
    echo "<script src='https://code.jquery.com/ui/1.14.1/jquery-ui.min.js' ></script>";
} elseif ($ua->judgeDevice() === SP_design) {
    echo "<script src='https://code.jquery.com/jquery-3.7.1.min.js' ></script>";
    echo "<script src='https://code.jquery.com/ui/1.14.1/jquery-ui.min.js' ></script>";
} else {
    var_dump('not');
}
