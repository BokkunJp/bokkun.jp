<?php

$commonWordPath = new \Path(dirname(__DIR__, 3));
$commonWordPath->addArray(["common", "Word", "Message.php"]);
require_once $commonWordPath->get();
// CSRFクラス
function setPublicCsrfErrorMessage()
{
    $addr = Public\Important\Setting::getRemoteAddr();
    $errMessage = "<p><strong>". gethostbyaddr($addr). "(". $addr. ")". "様のアクセスは禁止されています。</strong></p><p>以下の要因が考えられます。</p>";
    $errList = ["指定回数以上アクセスした。", "直接アクセスした。", "不正アクセスした。"];
    $errMessage .='<ul>';
    $errLists = '';
    foreach ($errList as $_errList) {
        $errLists .= "<li>{$_errList}</li>";
    }
    $errMessage .= $errLists;
    $errMessage .='</ul>';

    return $errMessage;
}

// 共通部分
$publicPath = new \Path(DOCUMENT_ROOT, '/');
$publicPath->setPathEnd();
$publicPath->add('public');
define('PUBLIC_DIR', $publicPath->get());

$publicPath = new \PathApplication("PUBLIC_COMMON_DIR", PUBLIC_DIR);
$publicPath->methodPath("EditSepartor", "/");
$publicPath->setAll([
    "PUBLIC_CLIENT_DIR" => '',
]);
$publicPath->resetKey("PUBLIC_CLIENT_DIR");
$publicPath->methodPath("Add", "client");
define('PUBLIC_CLIENT_DIR', $publicPath->get());

$publicPath->resetKey("PUBLIC_COMMON_DIR");
$publicPath->methodPath("SetEndPath");
$publicPath->methodPath("Add", "common");
define('PUBLIC_COMMON_DIR', $publicPath->get());

$publicPath = new \PathApplication("PUBLIC_COMPONENT_DIR", PUBLIC_COMMON_DIR);
$publicPath->setAll([
    "PUBLIC_LAYOUT_DIR" => COMMON_DIR,
]);
$publicPath->resetKey("PUBLIC_COMPONENT_DIR");
$publicPath->methodPath("Add", "Component");
define('PUBLIC_COMPONENT_DIR', $publicPath->get());

$publicPath->resetKey("PUBLIC_LAYOUT_DIR");
$publicPath->methodPath("Add", "layout");
define('PUBLIC_LAYOUT_DIR', $publicPath->get());

$publicPath = new \PathApplication("PUBLIC_CSS_DIR", PUBLIC_CLIENT_DIR);
$publicPath->setAll([
    "PUBLIC_CSS_DIR" => '',
    "PUBLIC_JS_DIR" => '',
    "PUBLIC_IMAGE_DIR" => '',
    "PUBLIC_3D_DIR" => '',
    "PUBLIC_CSV_DIR" => '',
]);
$publicPath->resetKey("PUBLIC_CSS_DIR");
$publicPath->methodPath("Add", "css");
define('PUBLIC_CSS_DIR', $publicPath->get());

$publicPath->resetKey("PUBLIC_JS_DIR");
$publicPath->methodPath("Add", "js");
define('PUBLIC_JS_DIR', $publicPath->get());

$publicPath->resetKey("PUBLIC_IMAGE_DIR");
$publicPath->methodPath("Add", "image");
define('PUBLIC_IMAGE_DIR', $publicPath->get());

$publicPath->resetKey("PUBLIC_3D_DIR");
$publicPath->methodPath("Add", "3d");
define('PUBLIC_3D_DIR', $publicPath->get());

$publicPath->resetKey("PUBLIC_CSV_DIR");
$publicPath->methodPath("Add", "csv");
define('PUBLIC_CSV_DIR', $publicPath->get());

// 画像閲覧ページ
define('PUBLIC_PREVIOUS', '画像閲覧ページへ戻る');
// メール送信ページ
define("DENY_SEND_DATE", 1);