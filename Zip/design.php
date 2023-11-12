<!-- デザイン用ファイル (PHPで処理を記述)-->
<form method='POST'>
    <button name='zip' value='1'>Zip圧縮する</button>
</form>
<?php

$post = Public\Important\Setting::getPost('zip');
if ($post) {
    $zip = new zipArchive;
    $path = PUBLIC_IMAGE_DIR. DIRECTORY_SEPARATOR .'Zip'. DIRECTORY_SEPARATOR .'test.zip';
    $filePath = PUBLIC_IMAGE_DIR. DIRECTORY_SEPARATOR .'Zip' . DIRECTORY_SEPARATOR . 'test';
    if ($zip->open($path, ZipArchive::CREATE) === true) {
        foreach (scandir($filePath) as $_file) {
            if ($_file !== '.' && $_file !== '..') {
                $_filePath = $filePath. DIRECTORY_SEPARATOR. $_file;
                $zip->addFile($_filePath, $_file);
            }   
        }
        $zip->close();
    }
}
