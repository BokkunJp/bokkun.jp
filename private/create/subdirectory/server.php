<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!isset($_SESSION)) {
    session_start();
}
define("DS", DIRECTORY_SEPARATOR);

// 関数定義 (初期処理用)
require dirname(__DIR__, 2) . DS . 'common' . DS . 'InitFunction.php';
// 設定
require_once dirname(__DIR__, 2) . DS . "common" . DS . "Setting.php";
// タグ
require_once dirname(__DIR__, 2) . DS . AddPath("common", "Component") . DS . "Tag.php";
// 定数・固定文言など
require_once AddPath(AddPath(AddPath(dirname(__DIR__, 2), "common", false), "Word", false), "Message.php", false);
// CSRF
require_once PRIVATE_COMMON_DIR . "/Token.php";

define('MAX_LENGTH', 32);
$adminError = new AdminError();
$use = new PrivateTag\UseClass();

$adminPath = dirname(__DIR__);
$samplePath = dirname($adminPath) . DIRECTORY_SEPARATOR . 'Sample';
$basePath = DOCUMENT_ROOT;
$session = $_SESSION;

// tokenチェック
$checkToken = CheckToken();

// 不正tokenの場合は、エラーを出力して処理を中断。
if ($checkToken === false) {
    $sessClass =  new PrivateSetting\Session();
    $sessClass->Write('notice', '<span class="warning">不正な遷移です。もう一度操作してください。</span>', 'Delete');
    $url = new PrivateSetting\Setting();
    $backUrl = CreateClient('private', dirname(__DIR__));
    $backUrl = ltrim($backUrl, DS);
    header('Location:' . $url->GetUrl($backUrl));
    exit;
}


$post = $_POST;
$judge = array();
foreach ($post as $post_key => $post_value) {
    $$post_key = $post_value;
    $judge[$$post_key] = $post_value;
}
if (!isset($type) || !isset($use_template_engine) ||  empty($title)) {
    if (!isset($session['addition'])) {
        $session['addition'] = $post;
        $_SESSION = $session;
    }
    unset($session);
    unset($post);
    $adminError->UserError('未記入の項目があります。');
} else {
    $pathList = ['php', 'js', 'css', 'image'];
    // ファイル存在チェック
    foreach ($pathList as $_pathList) {
        $client = $basePath . '/public/';
        if ($_pathList === 'php') {
            $client = $basePath;
        } else {
            $client .= "client/".
            $_pathList. "/";
        }
        foreach (scandir($client) as $file) {
            if (mb_strpos($file, '.') !== 0) {
                if ($file === $title) {
                    $adminError->UserError("ご指定のタイトルの" . $_pathList . "ファイルが存在します。ページの作成を中止します。");
                    // $adminError->UserError("ご指定のページは既に作成済みです。");
                }
            }
        }
    }
}
if (preg_match('/^[a-zA-Z][a-zA-Z+-_]*/', $title) === 0) {
    $adminError->UserError('タイトルに無効な文字が入力されています。');
} else if (strlen($title) > MAX_LENGTH) {
    $adminError->UserError("タイトルの文字数は、" . MAX_LENGTH . "文字以下にしてください。");
}

$baseFileName = $samplePath;

switch ($use_template_engine) {
    case 'smarty':
        $templateExtenion = 'tpl';
        break;
    case 'twig':
        $templateExtenion = 'twig';
        break;
    default:
        $templateExtenion = null;
        break;
}

chdir($basePath);

// 公開ディレクトリの作成
if (!is_dir($title)) {
    mkdir($title);
}

if (file_exists("$title") === false) {         // ディレクトリ作成
    $$adminError->UserError('ページの作成に失敗しました。');
}

// PHP部分で必要なファイルを作成
copy("$baseFileName/design.php", "$title/design.php");          // フォルダ内のdesgin.php作成
$fileName = 'index';
switch ($type) {
    case 'default':
        $srcfileName = $fileName . '_base';
        break;
    case 'scratch':
        $srcfileName = $fileName . '_scratch';
        break;
    default:
        $srcfileName = $fileName;
        break;
}

copy("$baseFileName/$srcfileName.{$pathList[0]}", "$title/$fileName.{$pathList[0]}");            // それぞれのフォルダに必要なファイルの作成

// デフォルト選択時以外は処理追加
if ($type === "scratch") {
    $fp = fopen("$title/$fileName.{$pathList[0]}", "a");
    if (fwrite($fp, ADD_DESIGN) === false) {
        $adminError->UserError('indexファイルのスクラッチ用の追記に失敗しました。');
    }
    fclose($fp);
} else if ($type === "custom") {
    // カスタム選択時には追加のディレクトリをコピー
    mkdir("$title/Layout");                               // Layoutディレクトリ作成

    foreach (scandir(AddPath($samplePath, 'Layout')) as $_file) {
        if (!is_dir($_file)) {
            copy("$baseFileName/Layout/{$_file}", "$title/Layout/{$_file}");
        }
    }
}

if (!empty($templateExtenion)) {
    copy("$baseFileName/$fileName.$templateExtenion", "$title/$fileName.$templateExtenion");  // テンプレートエンジン用のindexファイル作成
    if ($templateExtenion !== 'tpl') {
        mkdir("$title/subdirectory");                               // smarty未設定時、subdirectoryディレクトリ作成
    }
} else {
    mkdir("$title/subdirectory");                               // smarty未設定時、subdirectoryディレクトリ作成
}

unset($pathList[0]);

// js/css/imageフォルダの作成
chdir("public/");
foreach ($pathList as $_pathList) {
    if ($_pathList === 'js') {
        $client = "client/";
    } else {
        $client = "../";
    }
    chdir($client. $_pathList);               // パスの移動

    if (!is_dir($title)) {
        mkdir($title);
    }

    switch ($_pathList) {
        case 'image':
            break;
        case 'css':
            $fileName = 'design';
            copy("$samplePath/client/$_pathList/$fileName.$_pathList", "$title/$fileName.$_pathList");            // それぞれのフォルダに必要なファイルの作成
            break;
        case 'js':
            $fileName = 'index';
            copy("$samplePath/client/$_pathList/$fileName.$_pathList", "$title/$fileName.$_pathList");            // それぞれのフォルダに必要なファイルの作成
            break;
        default:
            break;
    }
}
if (!empty($templateExtenion)) {
    unlink("$basePath/$title/design.php");
    copy("$baseFileName/design" . "_$templateExtenion" . ".php", "$basePath/$title/design.php");            // design.phpファイル上書き
}

$use->Alert('ページを作成しました。');
// session_destroy();

class AdminError
{
    protected $use;
    public function __construct()
    {
        $this->use = new PrivateTag\UseClass();
    }

    public function UserError($message)
    {
        $this->use->Alert($message);
        $this->use->BackAdmin('create');
        exit;
    }

    public function Maintenance()
    {
        $this->UserError('当機能はメンテナンス中です。しばらくお待ちください。');
    }
}
?>

<head>
    <base href="../" />
</head>
<script>
    onload = function() {
        title = document.getElementsByName('title')[0].value;
        if (title) {
            title = location.protocol + '//' + location.host + '/' + title;
            open(title);
        }

        location.href = "./";
    }
</script>

<body>
    <input type="hidden" name="title" value="<?php echo $title; ?>" />
</body>