<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define("DS", DIRECTORY_SEPARATOR);
define('MAX_LENGTH', 32);

// 関数定義 (初期処理用)
require dirname(__DIR__, 2) . DS . 'common' . DS . 'InitFunction.php';
// 定数・固定文言など
require_once AddPath(AddPath(AddPath(dirname(__DIR__, 2), "common", false), "Word", false), "Message.php", false);
// 設定
require_once AddPath(PRIVATE_COMMON_DIR, "Setting.php", false);
// セッション
require_once AddPath(PRIVATE_COMMON_DIR, "Session.php", false);
// CSRF
require_once AddPath(PRIVATE_COMMON_DIR, "Token.php", false);
// タグ
require_once AddPath(PRIVATE_COMPONENT_DIR, "Tag.php", false);

$session =  new private\Session();
$adminError = new AdminError();
$use = new PrivateTag\UseClass();

$adminPath = dirname(__DIR__);
$samplePath = AddPath(dirname($adminPath), 'Sample');
$basePath = DOCUMENT_ROOT;

// tokenチェック
$checkToken = CheckToken();

// 不正tokenの場合は、エラーを出力して処理を中断。
if ($checkToken === false) {
    $session->Write('notice', '<span class="warning">不正な遷移です。もう一度操作してください。</span>', 'Delete');
    $url = new private\Setting();
    $backUrl = CreateClient('private', dirname(__DIR__));
    $backUrl = ltrim($backUrl, DS);
    header('Location:' . $url->GetUrl($backUrl));
    exit;
}

$post = private\Setting::GetPosts();
$judge = array();
foreach ($post as $post_key => $post_value) {
    $$post_key = $post_value;
    $judge[$$post_key] = $post_value;
}

// 内容をセッションに保存し、不要なデータを破棄
if (!$session->JudgeArray('admin', 'addition')) {
    $session->WriteArray('admin', 'addition', $post);
}
unset($session);
unset($post);


if (!isset($type) || !isset($use_template_engine) ||  empty($title)) {
    $adminError->UserError('未記入の項目があります。');
} else {
    // 文字チェック
    if (preg_match('/^[a-zA-Z][a-zA-Z0-9-_+]*$/', $title) === 0 ||!FindFileName($title) === 0) {
        $adminError->UserError('タイトルに無効な文字が入力されています。');
    } elseif (strlen($title) > MAX_LENGTH) {
        $adminError->UserError("タイトルの文字数は、" . MAX_LENGTH . "文字以下にしてください。");
    }

    $pathList = ['php', 'js', 'css', 'image'];
    // ファイル存在チェック
    foreach ($pathList as $_path) {
        $client = $basePath . '/public/';
        if ($_path === 'php') {
            $client = $basePath;
        } else {
            $client .= "client/".
            $_path. "/";
        }

        // 大文字・小文字関係なく、同一ページが存在するかチェック
        $result = ValidateData($client, strtolower($title));

        if (!$result) {
            $result = ValidateData($client, strtoupper($title));
        }

        // commonの名称は作成不可
        if ($title === 'common' || $title === 'public') {
            $adminError->UserError('その名称のページは作成できません。');
        }

        // 存在する場合は上書き
        if ($result) {
            $use->Alert("指定されたページには{$_path}ファイルが存在します。既存の内容は上書きされます。");
        }
    }
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
} elseif ($type === "custom") {
    // カスタム選択時には追加のディレクトリをコピー
    if (!file_exists("$title/Layout")) {
        mkdir("$title/Layout");                               // Layoutディレクトリ作成
    }

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
    if (!file_exists("$title/subdirectory")) {
        mkdir("$title/subdirectory");                               // smarty未設定時、subdirectoryディレクトリ作成
    }
}

unset($pathList[0]);

// js/css/imageフォルダの作成
chdir("public/");
foreach ($pathList as $_path) {
    if ($_path === 'js') {
        $client = "client/";
    } else {
        $client = "../";
    }
    chdir($client. $_path);               // パスの移動

    if (!is_dir($title)) {
        mkdir($title);
    }

    switch ($_path) {
        case 'image':
            break;
        case 'css':
            $fileName = 'design';
            copy("$samplePath/client/$_path/$fileName.$_path", "$title/$fileName.$_path");            // それぞれのフォルダに必要なファイルの作成
            break;
        case 'js':
            $fileName = 'index';
            copy("$samplePath/client/$_path/$fileName.$_path", "$title/$fileName.$_path");            // それぞれのフォルダに必要なファイルの作成
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
    onload =  function () {
        title = document.getElementsByName('title')[0].value;
        if (title) {
            title = location.protocol + '//' + location.host + '/' + title;
            open(title);
        }

        location.href = "./";
    }
</script>

<body>
    <input type="hidden" name="title"
        value="<?php echo $title; ?>" />
</body>