<?php
function MakeCSV($fileName, $data='1, 2, 3') {
    var_dump($fileName);
}

function Validate($haystack) {
    $ret = true;
    if (!is_string($haystack)) {
        $ret = false;
    }
    
    var_dump($haystack);
    var_dump(strpos($haystack, '/^(*).\.csv$/'));

//    if (strpos())

    return $ret;
}