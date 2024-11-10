<?php

$commonWordPath = new \Path(dirname(__DIR__, 3));
$commonWordPath->addArray(["common", "Word", "Message.php"]);
require_once $commonWordPath->get();

// 共通部分
// ドキュメントルートをセットして定数を定義
$publicPath = new \Path(DOCUMENT_ROOT, '/');
$publicPath->setPathEnd();
$publicPath->add('public');
define('PUBLIC_DIR', $publicPath->get());

// 公開領域のパスを定義
$publicPath = new \PathApplication("PUBLIC_COMMON_DIR", PUBLIC_DIR);
$publicPath->methodPath("editSeparator", "/");
$publicPath->setAll([
    "PUBLIC_CLIENT_DIR" => '',
]);
$publicPath->setKey("PUBLIC_CLIENT_DIR");
$publicPath->methodPath("Add", "client");
define('PUBLIC_CLIENT_DIR', $publicPath->get());

// 共通パス(公開側非公開領域)の定数を定義
$publicPath->setKey("PUBLIC_COMMON_DIR");
$publicPath->methodPath("setPathEnd");
$publicPath->methodPath("Add", "common");
define('PUBLIC_COMMON_DIR', $publicPath->get());

// 非公開領域パスの定数を定義
$publicPath = new \PathApplication("PUBLIC_COMPONENT_DIR", PUBLIC_COMMON_DIR);
$publicPath->setAll([
    "PUBLIC_LAYOUT_DIR" => COMMON_DIR,
]);
$publicPath->setKey("PUBLIC_COMPONENT_DIR");
$publicPath->methodPath("add", "Component");
define('PUBLIC_COMPONENT_DIR', $publicPath->get());

// レイアウトパス(公開領域)の定数を定義
$publicPath->setKey("PUBLIC_LAYOUT_DIR");
$publicPath->methodPath("Add", "layout");
define('PUBLIC_LAYOUT_DIR', $publicPath->get());

// クライアント内のパスをまとめて定義
$publicPath = new \PathApplication("PUBLIC_CLIENT_DIR", PUBLIC_CLIENT_DIR);
$publicClientList = [
    "PUBLIC_CSS_DIR" => 'css',
    "PUBLIC_JS_DIR" => 'js',
    "PUBLIC_IMAGE_DIR" => 'image',
    "PUBLIC_3D_DIR" => '3d',
    "PUBLIC_CSV_DIR" => 'csv',
    "PUBLIC_ZIP_DIR" => 'zip',
];

// パス定数リストをセット
$publicPath->setAll($publicClientList);

// パス定数をまとめて定義
$publicPath->setKey("PUBLIC_CLIENT_DIR");
$publicClientpath = $publicPath->get();
$publicDirList = [];
foreach ($publicClientList as $_dir => $_value) {
    $publicPath->setKey($_dir);
    $publicDirList[$_value] = $publicClientpath. $publicPath->get();
}

define("PUBLIC_DIR_LIST", $publicDirList);

unset($publicPath);
unset($publicDirList);

// 画像閲覧ページ
define('PUBLIC_PREVIOUS', '画像閲覧ページへ戻る');
// メール送信ページ
define("DENY_SEND_DATE", 1);
