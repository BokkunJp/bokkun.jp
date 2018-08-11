<?php
ini_set('error_reporting', E_ALL | ~E_STRICT);
require_once dirname(dirname(__DIR__)). '/common/Setting.php';
require_once COMMON_DIR. "/Include.php";
// タイトルの初期設定
if (isset($homepageTitle)) {
    $title = htmlspecialchars($homepageTitle);
} else {
    $title = htmlspecialchars(basename(__DIR__)); 
}
$agent = new UA\UA();
define('Phone', 2);
define('PC', 1);
$statusCode = $agent->designJudege();
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

// 文字列からディレクトリ部分の文字列を切り取る関数
function StrExtraction($str, $target) {
    $count = strpos($str, $target);
    return mb_strcut($str, $count);
}

// パンくずリストの生成 (完成・検証後にSetting.phpに移動)
$dir = scandir(__DIR__);
$currentDir = PublicSetting\GetURI();
// $currentDir = getURI_Admin();
$count = 0;
while (1) {
    // タイトル：ディレクトリ名、パス：URL
    $breadCrumbList[$count] = array();
    $breadCrumbList[$count]['title'] = basename($currentDir);
    $breadCrumbList[$count]['path'] = $domain.$currentDir;
    if ($currentDir === DIRECTORY_SEPARATOR) {
        // ルートディレクトリはそれぞれの設定をドメイン名に上書き
        // (Linux環境はROOT定数, Windows環境は円マーク)
        $breadCrumbList[$count]['title'] = $domain;
        $breadCrumbList[$count]['path'] = $domain;
        break;
    } else {
        $count++;
    }
    $currentDir = dirname($currentDir);
}

// HTML出力用に調整
$create = new CustomTagCreate();
$breadCrumbList_ = array();
foreach ($breadCrumbList as $bread) {
    $breadCrumbList_[] = $create->SetHref($http.$bread['path'], $bread['title'], 'breadCrumbList');
}
$breadCrumbList_ = array_reverse($breadCrumbList_);
$breadCrumbList = $breadCrumbList_;
unset($breadCrumbList_);

$arrow = new HTMLClass(true);
$arrow->TagSet('span', '->', 'arrow', true);
$arrow = $arrow->TagExec();

// 配列を順に出力する (パンくず出力用)
function ViewArray($ary, $arow="\t") {
    foreach ($ary as $_elm => $_ary) {
        // 配列の末尾では間の文字は消す
        if ($_elm === (count($ary) - 1)) {
            $arow = '';
        }
        echo $ary[$_elm]. $arow;
    }
}