<?php
// タイトルの初期設定
if (isset($homepageTitle)) {
    $title = htmlspecialchars($homepageTitle);
} else {
    $title = htmlspecialchars(basename(__DIR__));
}

// パンくずリストの生成 (完成・検証後にSetting.phpに移動)
$dir = scandir(__DIR__);
$currentDir = $base->GetURI();
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
use \PublicTag\CustomTagCreate as OriginTag;

$create = new OriginTag();
$breadCrumbList = array();
foreach ($breadCrumbList as $bread) {
    $breadCrumbList[] = $create->SetHref($http.$bread['path'], $bread['title'], 'breadCrumbList');
}
$breadCrumbList = array_reverse($breadCrumbList);
$breadCrumbList = $breadCrumbList;

$arrow = new \PublicTag\HTMLClass(true);
$arrow->TagSet('span', '->', 'arrow', true);
$arrow = $arrow->TagExec();

// 配列を順に出力する (パンくず出力用)
function ViewArray($ary, $arow="\t") {
    foreach ($ary as $_elm => $_ary) {
        // 配列の末尾では間の文字(矢印)は消す
        if ($_elm === (count($_ary) - 1)) {
            $arow = '';
        }
        echo $ary[$_elm]. $arow;
    }
}
