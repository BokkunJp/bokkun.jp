<!-- デザイン用ファイル (PHPで処理を記述)-->
<form method='POST'>
    <button name='zip' value='1'>Zip圧縮する</button>
</form>
<?php
use Public\Important\Setting as Setting;

$post = Public\Important\Setting::getPost('zip');
$path = PUBLIC_ZIP_DIR. basename(__DIR__). DIRECTORY_SEPARATOR . basename(__DIR__). '.zip';
if ($post) {
    if (!is_dir(PUBLIC_ZIP_DIR. basename(__DIR__))) {
        mkdir(PUBLIC_ZIP_DIR. basename(__DIR__));
    }

    $zip = new zipArchive;
    $filePath = PUBLIC_IMAGE_DIR .basename(__DIR__);   

    if ($zip->open($path, ZipArchive::OVERWRITE) === true) {
        foreach (scandir($filePath) as $_file) {
            if ($_file !== '.' && $_file !== '..') {
                $_filePath = $filePath. DIRECTORY_SEPARATOR. $_file;
                if (!empty($filePath) && is_file($_filePath)) {
                    $addJudge = $zip->addFile($_filePath, $_file);
                }
            }   
        }
        $zip->close();
    }
}

$base = new Setting();
$zipPath = $base->getUrl(). DIRECTORY_SEPARATOR. basename(PUBLIC_ZIP_DIR). DIRECTORY_SEPARATOR. basename(__DIR__). DIRECTORY_SEPARATOR . basename(__DIR__). '.zip';
echo "<p>";
echo "<a href=\"{$zipPath}\" download>ダウンロード</a> <br/>";
echo "</p>";