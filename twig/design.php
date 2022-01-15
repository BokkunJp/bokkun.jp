<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
SetPlugin('twig');
$loader = new FilesystemLoader('/');
$autoLoadFlg = false;

if (PublicSetting\Setting::GetServarName() !== 'bokkun.jp') {
    $autoLoadFlg = true;
}
$twig = new Environment($loader, [
    'cache' => './subdirectory/template_cache',
    'auto_reload' => $autoLoadFlg,
]);

$twig->addGlobal('twig', 'Twig 3');
$twig->display('index.twig');
