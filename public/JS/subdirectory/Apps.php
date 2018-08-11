<?php
function ViewFunc() {
    echo 'View Function <br />';
}

function ReturnFunc() {
    return 'return Function : '. 1;
}

function CallFunc($funcName, $x=null, $y=null) {
    $fN = $funcName($x, $y);
    if (isset($fN)) {
        return $fN;
    }
}

function CalcFunction($x, $y) {
    return $x + $y;
}