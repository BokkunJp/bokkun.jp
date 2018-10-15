<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
// 定義部
require_once (DOCUMENT_ROOT. '/API/smarty/core.php');

use CustomTagCreate as OriginTag;

$smarty->assign('name', 'guest');

//$create = new HTMLClass();
//$create->TagSet('span', 'spanタグ', 'classA', true, true);
//$span = $create->TagExec();
//$create->TagSet('div', "divタグ$span", 'classB', true, true);
//
//$smarty->assign('newTag', $create->TagExec());

$test = new OriginTag();

$smarty->assign('inputSelect', $test->authorityListCreate(['script']));

$smarty->display('index.tpl');

$test->SetHref('http://bokkun.jp', 'トップページへ');
?>
