<?php
function Test2() {
    return 1;
}
function DirSet($target, $base=__DIR__) {
    $ret = $base . $target;

    chdir($base.$target);
}
