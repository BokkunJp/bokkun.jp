<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

setPlugin('twig');
$loader = new FilesystemLoader('/');
$autoLoadFlg = false;

if (public\Setting::GetServerName() !== 'bokkun.jp') {
    $autoLoadFlg = true;
}
$twig = new Environment($loader, [
    'cache' => './subdirectory/template_cache',
    'auto_reload' => $autoLoadFlg,
]);

$twig->addGlobal('twig', 'Twig 3');
$twig->display('index.twig');
