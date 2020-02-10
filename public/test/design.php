<?php
$dir = AddPath(__DIR__, 'subdirectory');
$dir = AddPath($dir, 'Tuning');

IncludeFiles($dir, 'php', false, ['name' => 'App']);

$test = new Tuning\App();
$test->SetValue(12);
$ret = $test->CallBack("Test");
SearchBinary([1, 2, 3], 'a');

function SearchBinary(Array $data, $target)
{
    $html = new PublicTag\HTMLClass();
    if (!is_numeric($target)) {
        $html->TagSet('span', '不正な値です。', 'error');
        $html->TagExec(true);
        return null;
    }
}
