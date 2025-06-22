<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
ErrorConfig::secureMode();

class Qr
{
    use PublicTrait;
}
$qr = new Qr();
$qr->makeQrCode('test',['hoge' => 'H']);
// $qr->makeQrCode('test',['hoge' => 'H'], 'test.png');