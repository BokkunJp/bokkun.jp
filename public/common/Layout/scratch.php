<!DOCTYPE html>
<?php

require_once __DIR__. '/require.php';

// PHPファイル読み込み
$fileLists = scandir(PAGE_ROOT);
foreach ($fileLists as $file) {
    if (findFileName($file)) {
        $filePath = new \Path(__DIR__);
        $filePath->setPathEnd();
        $filePath->add($file);
        if (file_exists($filePath->get())) {
            // index.phpは読み込まれない
            require_once $filePath->get();
        }
    }
}
