<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
class Qr
{
    use PublicTrait;
}

$qrTest = new Qr();
$qrTest->makeQrCode(100, 'test', true);
$qrTest->makeQrCode(100, 'bokkun', true);
$qrTest->makeQrCode(100, 'https://bokkun.jp', true);
