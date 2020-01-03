<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once dirname(__DIR__) . "/common/Setting.php";
require_once PRIVATE_COMPONENT_DIR . '/UA.php';
require_once PRIVATE_COMMON_DIR . "/Token.php";

// UA判定処理
$ua = new UA\UA();
define('Phone', 2);
define('PC', 1);
switch ($ua->DesignJudge()) {
    case PC:
        $agentCode = 'PC';
        break;
    case Phone:
        $agentCode = 'SMP';
        break;
    default:
        break;
}

require_once PRIVATE_COMMON_DIR . "/Include.php";
