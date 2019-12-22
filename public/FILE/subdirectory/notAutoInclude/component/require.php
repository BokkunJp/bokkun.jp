<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once dirname(dirname(dirname(dirname(__DIR__)))) . '/common/Setting.php';
require_once PUBLIC_COMPONENT_DIR . '/UA.php';
require_once PUBLIC_COMMON_DIR . "/Token.php";
// ユーザーエージェントの設定
$ua = new UA\UA();
define('Phone', 2);
define('PC', 1);
$statusCode = $ua->DesignJudege();
switch ($statusCode) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}
require_once "Include.php";
