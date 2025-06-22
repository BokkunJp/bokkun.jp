<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
ErrorConfig::secureMode();

class Qr
{
    use PublicTrait;
}
$qr = new Qr();
// $qr->cmakecQrCode('test',['eccLevel' => 'H'],  true);
$qr->makeQrCode('test',['hoge' => 'H'], 'test.png');
// $qr->cmakecQrCode('test','H', 1,  true);