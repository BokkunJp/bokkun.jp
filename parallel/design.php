<?php
require_once __DIR__. DIRECTORY_SEPARATOR. 'subdirectory'. DIRECTORY_SEPARATOR. 'parallel.php';

// CLIのみで動作するので応急策
// (そのうちfastCGIで変更予定)
$test = new Parallel();
$test->exec('run', function(){
    sleep(1);
    echo 'サブ', PHP_EOL;
});

// if (PHP_SAPI !== 'cli') {
//     debug(PHP_SAPI);
//     return false;
// }

// $runtime = new \parallel\Runtime();

// $runtime->run(function(){
//     sleep(1);
//     echo 'サブ', PHP_EOL;
// });

echo 'メイン', PHP_EOL;
