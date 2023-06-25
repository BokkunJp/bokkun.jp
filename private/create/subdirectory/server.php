<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define("DS", DIRECTORY_SEPARATOR);
define('MAX_LENGTH', 32);

/* 定義・呼び出し処理 */
// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DS . 'common' . DS . 'InitFunction.php';

// パスの初期セット
$privatepathList = new PathApplication('word', dirname(__DIR__, 2));

// それぞれの変数セット
$privatepathList->SetAll(
    [
        'setting' => '',
        'include' => '',
        'session' => '',
        'token' => '',
        'common' => '',
        'ua' => '',
        'config' => dirname(__DIR__, 3),
    ]
);

// パスの追加
// ヘッダー・フッター
$privatepathList->ResetKey('config');
$privatepathList->MethodPath('AddArray', ['common', 'Config.php']);

// 定数・固定文言など
$privatepathList->ResetKey('word');
$privatepathList->MethodPath('AddArray', ['common', 'Word', 'Message.php']);

// 管理側共通(ログイン認証など)
$privatepathList->ResetKey('common');
$privatepathList->MethodPath('AddArray', ['common.php']);

// 設定
$privatepathList->ResetKey('setting');
$privatepathList->MethodPath('AddArray', ['common', 'Setting.php']);

// セッション
$privatepathList->ResetKey('session');
$privatepathList->MethodPath('AddArray', ['common', 'Session.php']);

// トークン
$privatepathList->ResetKey('token');
$privatepathList->MethodPath('AddArray', ['common', 'Token.php']);

// ファイル読み込み
$privatepathList->ResetKey('include');
$privatepathList->MethodPath('AddArray', ['common', 'Include.php']);

// UA
$privatepathList->ResetKey('ua');
$privatepathList->MethodPath('AddArray', ['common', 'Component', 'UA.php']);

// パスの出力
$privatepathList->All();
foreach ($privatepathList->Get() as $path) {
    require_once $path;
}

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

$session =  new private\Session();
$adminError = new AdminError();
$use = new PrivateTag\UseClass();

$adminPath = dirname(__DIR__);
$samplePath = new \Path(dirname($adminPath));
$samplePath->Add('Sample');
$samplePath = $samplePath->Get();
$basePath = DOCUMENT_ROOT;

// tokenチェック
$createToken = new private\Token('create-token', $session);
if ($createToken->Check() === false) {
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

    $oldSamplePath = new \Path($samplePath);
    $samplePath = new \Path($samplePath);
    $samplePath->Add("Layout");
    $samplePath = $samplePath->Get();
    foreach (scandir($samplePath) as $_file) {
        if (!is_dir($_file)) {
            copy("$baseFileName/Layout/{$_file}", "$title/Layout/{$_file}");
        }
    }
    $samplePath = $oldSamplePath->Get();
}

// テンプレートを指定した場合は、テンプレートエンジン用のindexファイル作成
if (!empty($templateExtenion)) {
    copy("$baseFileName/$fileName.$templateExtenion", "$title/$fileName.$templateExtenion");
} elseif (!file_exists("$title/subdirectory")) {
    // テンプレートの未設定かつ初期作成時、subdirectoryディレクトリ作成
    mkdir("$title/subdirectory");
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