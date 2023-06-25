<?php

$commonWordPath = new \Path(dirname(__DIR__, 3));
$commonWordPath->AddArray(["common", "Word", "Message.php"]);
require_once $commonWordPath->Get();
// CSRFクラス
function Public_CSRFErrorMessage()
{
    $addr = public\Setting::GetRemoteADDR();
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
$publicPath->SetPathEnd();
$publicPath->Add('public');
define('PUBLIC_DIR', $publicPath->Get());

$publicPath = new \PathApplication("PUBLIC_COMMON_DIR", PUBLIC_DIR);
$publicPath->MethodPath("EditSepartor", "/");
$publicPath->SetAll([
    "PUBLIC_CLIENT_DIR" => '',
]);
$publicPath->ResetKey("PUBLIC_CLIENT_DIR");
$publicPath->MethodPath("Add", "client");
define('PUBLIC_CLIENT_DIR', $publicPath->Get());

$publicPath->ResetKey("PUBLIC_COMMON_DIR");
$publicPath->MethodPath("SetEndPath");
$publicPath->MethodPath("Add", "common");
define('PUBLIC_COMMON_DIR', $publicPath->Get());

$publicPath = new \PathApplication("PUBLIC_COMPONENT_DIR", PUBLIC_COMMON_DIR);
$publicPath->SetAll([
    "PUBLIC_LAYOUT_DIR" => COMMON_DIR,
]);
$publicPath->ResetKey("PUBLIC_COMPONENT_DIR");
$publicPath->MethodPath("Add", "component");
define('PUBLIC_COMPONENT_DIR', $publicPath->Get());

$publicPath->ResetKey("PUBLIC_LAYOUT_DIR");
$publicPath->MethodPath("Add", "layout");
define('PUBLIC_LAYOUT_DIR', $publicPath->Get());

$publicPath = new \PathApplication("PUBLIC_CSS_DIR", PUBLIC_CLIENT_DIR);
$publicPath->SetAll([
    "PUBLIC_CSS_DIR" => '',
    "PUBLIC_JS_DIR" => '',
    "PUBLIC_IMAGE_DIR" => '',
    "PUBLIC_3D_DIR" => '',
    "PUBLIC_CSV_DIR" => '',
]);
$publicPath->ResetKey("PUBLIC_CSS_DIR");
$publicPath->MethodPath("Add", "css");
define('PUBLIC_CSS_DIR', $publicPath->Get());

$publicPath->ResetKey("PUBLIC_JS_DIR");
$publicPath->MethodPath("Add", "js");
define('PUBLIC_JS_DIR', $publicPath->Get());

$publicPath->ResetKey("PUBLIC_IMAGE_DIR");
$publicPath->MethodPath("Add", "image");
define('PUBLIC_IMAGE_DIR', $publicPath->Get());

$publicPath->ResetKey("PUBLIC_3D_DIR");
$publicPath->MethodPath("Add", "3d");
define('PUBLIC_3D_DIR', $publicPath->Get());

$publicPath->ResetKey("PUBLIC_CSV_DIR");
$publicPath->MethodPath("Add", "csv");
$publicPath->MethodPath("Add", "csv");
define('PUBLIC_CSV_DIR', $publicPath->Get());

// 画像閲覧ページ
define('PUBLIC_PREVIOUS', '画像閲覧ページへ戻る');
// メール送信ページ
define("DENY_SEND_DATE", 1);