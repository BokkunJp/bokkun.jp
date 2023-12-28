<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader('/');
$autoLoadFlg = false;

if (Public\Important\Setting::getServerName() !== 'bokkun.jp') {
    $autoLoadFlg = true;
}
$twig = new Environment($loader, [
    'cache' => './subdirectory/template_cache',
    'auto_reload' => $autoLoadFlg,
]);

$twig->addGlobal('twig', 'Twig 3');
$twig->display('index.twig');
