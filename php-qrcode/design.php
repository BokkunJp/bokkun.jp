<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
ErrorConfig::secureMode();

class cQr
{
    use PublicTrait;
}
$qr = new cQr();
// $qr->cmakecQrCode('test',['eccLevel' => 'H'],  true);
$qr->cmakecQrCode('test',['hoge' => 'H'], 'test.png');
// $qr->cmakecQrCode('test','H', 1,  true);