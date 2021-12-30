<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

SetPlugin('twig');
$loader = new FilesystemLoader('/');
$twig = new Environment($loader, [
    'cache' => './subdirectory/template_cache',
]);

$twig->display('index.twig', array('twig' => 'Twig 3'));