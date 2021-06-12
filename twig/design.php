<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
SetPlugin('twig');
$loader = new Twig_Loader_Filesystem('/');
$twig = new Twig_Environment($loader);
$template = $twig->loadTemplate('index.twig');
$template->display(array('twig' => 'Twig 2'));
