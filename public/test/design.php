<?php
$i = 1;
if ($i === 2) {
    echo 'test1';
} else {
    echo 'test2';
}
Stop();
function Stop() {
    return -1;
}