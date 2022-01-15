<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

SetPlugin('twig');
$loader = new FilesystemLoader('/');
$autoLoadFlg = false;

if (PrivateSetting\Setting::GetServarName() !== 'bokkun.jp') {
    $autoLoadFlg = true;
}
$twig = new Environment($loader, [
    'cache' => './subdirectory/template_cache',
    'auto_reload' => $autoLoadFlg,
]);

if (!isset($session)) {
    $session = new PrivateSetting\Session();
}

if ($session->Judge('addition')) {
    $twig->addGlobal('session', $session->Read('addition'));
    $session->Delete('addition');
}

$dir = scandir('../../');

foreach ($dir as $_key => $_dir) {
    if (preg_match("/public$|private$|common$|custom.*$|template.*$|\..*$/", $_dir)) {
        unset($dir[$_key]);
    }
}

if (!$session->Judge('token')) {
    $token = MakeToken();
    SetToken($token);
} else {
    $token = $session->Read('token');
}

$session->OnlyView('notice');

$twig->addGlobal('base', 'subdirectory');
$twig->addGlobal('token', $token);
$twig->addGlobal('dir', $dir);
$twig->addGlobal('maxCount', count($dir));
$twig->display('index.twig');
