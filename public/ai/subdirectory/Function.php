<?php
function SetFunc($funcName, $elm=null) {
    $val = $funcName($elm);
    if (isset($val)) {
        return $val;

    }
}