<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
class Qr {
    use PublicTrait;
}

$qrTest = new Qr();
$qrTest->MakeQrCode(100, 'test', true);
$qrTest->MakeQrCode(100, 'bokkun', true);
$qrTest->MakeQrCode(100, 'https://bokkun.jp', true);
