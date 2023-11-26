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

    if ($zip->open($path, ZipArchive::OVERWRITE | ZipArchive::CREATE) === true) {
        $sTime = hrtime(true);
        foreach (scandir($filePath) as $_file) {
            if ($_file !== '.' && $_file !== '..') {
                $_filePath = $filePath. DIRECTORY_SEPARATOR. $_file;
                if (is_file($_filePath)) {
                    $zip->addFile($_filePath, $_file);
                }
            }
        }
        $zip->close();

        $time = hrtime(true) - $sTime;
        $time = bcdiv($time, pow(10, 9), 7);
        output("<p>実行時間: {$time}秒</p>", false, false);
    }
}

$base = new Setting();
$zipUrl = $base->getUrl(). DIRECTORY_SEPARATOR. basename(PUBLIC_ZIP_DIR). DIRECTORY_SEPARATOR. basename(__DIR__). DIRECTORY_SEPARATOR . basename(__DIR__). '.zip';
if (file_exists($path)) {
    echo "<p><a href=\"{$zipUrl}\" download>ダウンロード</a></p>";
}
