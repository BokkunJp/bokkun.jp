<link rel="stylesheet" type="text/css" href="client/css/common.css">
<?php
    ini_set('error_reporting', E_ALL | ~E_STRICT);
    require_once dirname(dirname(__DIR__)). "/common/Setting.php";
    require_once dirname(__DIR__). "/common/Include.php";
    require_once('header.php');
    require_once(getcwd().'/design.php');
    require_once('footer.php');
