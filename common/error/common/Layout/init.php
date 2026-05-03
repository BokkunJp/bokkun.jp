<?php

// アクセスしたページが既存ページの大文字・小文字の違いであれば、既存ページに遷移
$root = dirname(__DIR__, 4);
$pageList = scandir($root);
$requestUri = basename($_SERVER['REQUEST_URI']);

$notAccessList = ['common', 'public'];

if (
    $requestUri !== 'private' && !preg_match("/^\_.*$/", $requestUri) &&
    !in_array($requestUri, $pageList)
    ) {
    foreach ($pageList as $_page) {
        if (
            !preg_match("/^\..*$/", $_page) && is_dir($root. DIRECTORY_SEPARATOR .$_page) &&
            stripos($_page, $requestUri) === 0 &&
            is_dir($root . DIRECTORY_SEPARATOR. $_page) &&
            !in_array($_page, $notAccessList)
        ) {
                header('Location:https://'.$_SERVER['SERVER_NAME'].'/'.$_page);
                exit;
        }
    }
}

?>
<!DOCTYPE html>
<?php

ini_set('error_reporting', E_ALL);

require_once dirname(__DIR__, 3). DIRECTORY_SEPARATOR. 'InitFunction.php';

// タイトルの初期設定
$errCode = http_response_code();    // ステータスコードを出力
if (!isset($title) && empty($title)) {
    $title = 'Page Error -';            // タイトル用に調整
    $title .= $errCode;
    $title .= '-';
}

$initPathList = new \PathApplication('word', dirname(__DIR__));
$initPathList->setAll([
    'setting' => dirname(__DIR__, 3),
    'error_setting' => dirname(__DIR__),
    'error_include' => ''
]);
$initPathList->setKey('word');
$initPathList->methodPath('setPathEnd');
$initPathList->methodPath('Add', 'word.php');
$initPathList->setKey('setting');
$initPathList->methodPath('setPathEnd');
$initPathList->methodPath('Add', 'Setting.php');
$initPathList->setKey('error_setting');
$initPathList->methodPath('setPathEnd');
$initPathList->methodPath('Add', 'Setting.php');
$initPathList->setKey('error_include');
$initPathList->methodPath('setPathEnd');
$initPathList->methodPath('Add', 'Include.php');

$initPathList->resetKey();
foreach ($initPathList->get() as $path) {
    require_once $path;
}

// 管理側かつ未ログインの場合は、正常遷移扱いにして管理側ログイン画面へ遷移
if (str_contains(Error\Important\Setting::getUri(), 'private')) {
    
    // http_response_code(200);
    $allSession = new Common\Important\Session();
    
}

// UA判定処理 (内容はベースと同様)
$agent = new Error\Important\Ua();
const PHONE = 2;
const PC = 1;
$agentCode = $agent->judgeDevice();