<!DOCTYPE html>
<?php

require_once __DIR__. '/require.php';

// PHPファイル読み込み
$scratchRoot = PAGE_ROOT;
$fileLists = scandir($scratchRoot);
foreach ($fileLists as $file) {
    if (findFileName($file)) {
        $filePath = new \Path($scratchRoot);
        $filePath->setPathEnd();
        $filePath->add($file);
        if (file_exists($filePath->get())) {
            // require_onceのため、index.phpは読み込まれない
            require_once $filePath->get();
        }
    }
}
