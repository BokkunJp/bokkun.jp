<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once dirname(dirname(__DIR__)). '/common/Setting.php';
require_once DOCUMENT_ROOT. '/common/Function/Tag.php';
define('MAX_LENGTH', 32);

$adminError = new AdminError();
$use = new UseClass();

$adminPath = dirname(__DIR__);
$basePath = DOCUMENT_ROOT;

session_start();
$session = $_SESSION;
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
        $client = $basePath. '/public/';
        if ($_pathList !== 'php') {
            $client .= "client/$_pathList/";
        }
        foreach (scandir($client) as $file) {
            if (mb_strpos($file, '.') !== 0) {
                if ($file === $title) {
                    $adminError->UserError("ご指定のタイトルの". $_pathList. "ファイルが存在します。ページの作成を中止します。");
                    // $adminError->UserError("ご指定のページは既に作成済みです。");
                }
            }
        }

    }
}
if (preg_match('/^[a-zA-Z][a-zA-Z+-_]*/', $title) === 0) {
    $adminError->UserError('タイトルに無効な文字が入力されています。');
} else if (strlen($title) > 32) {
        $adminError->UserError("タイトルの文字数は、". MAX_LENGTH. "文字以下にしてください。");
}

$baseFileName = null;
if ($type === 'default') {
    if ($use_template_engine === 'on') {
        $baseFileName = 'template';
    } else {
        $baseFileName = 'template_base';
    }
} else if ($type === 'custom') {
    if ($use_template_engine === 'on') {
        $baseFileName = 'custom';
    } else {
        $baseFileName = 'custom_base';
    }
} else {
    $adminError->UserError('タイプに不正値が入力されました。');
}

chdir($basePath);
// フォルダ・ファイルの作成
foreach ($pathList as $_pathList) {
    if ($_pathList === 'php') {
        chdir("public/");
    } else {
        if ($_pathList === 'js') {
            $client = "client/";
            $adminPath .= '/'. $client;
        } else {
            $client = "../";
        }
        chdir("$client$_pathList");               // パスの移動
    }
    mkdir($title);
    if (file_exists("$title") === false) {         // ディレクトリ作成
        $$adminError->UserError('ページの作成に失敗しました。');
    }

    // カスタム版の場合、CommonのLayoutの中身をコピーし、public下に配置する
    if ($type === 'custom') {         // ディレクトリ作成
        $adminError->UserError('ページの作成に失敗しました。');
    }

    if ($_pathList === 'image') {
        break;
    }

    if ($_pathList === 'css') {
        $fileName = 'design';
    } else {
        $fileName = 'index';
    }
    copy("$baseFileName/$fileName.$_pathList", "$title/$fileName.$_pathList");            // フォルダ内のindex.php作成
    if ($_pathList === 'php') {
        copy("$baseFileName/design.php", "$title/design.php");          // フォルダ内のdesgin.php作成
        if ($use_template_engine === 'on') {
            copy("$baseFileName/index.tpl", "$title/index.tpl");        // smarty設定時、index.tpl作成
        } else {
            mkdir("$title/subdirectory");                               // smarty未設定時、subdirectoryディレクトリ作成
        }
    }
}
$use->Alert('ページを作成しました。');
session_destroy();

class AdminError {
    protected $use;
    public function __construct() {
        $this->use = new UseClass();
    }

    public function UserError($message) {
        $this->use->Alert($message);
        $this->use->BackAdmin('create');
        exit;
    }

    public function Maintenance() {
        $this->UserError('メンテナンス中です。しばらくお待ちください。');
    }
}
?>
<head>
    <base href="../" />
</head>
<script>
onload = function () {
    title = document.getElementsByName('title')[0].value;
    if (title) {
        title = location.protocol + '//' + location.host + '/public/' + title;
        open(title);
    }

    location.href="./";
}
</script>

<body>
    <input type="hidden" name="title" value="<?php echo $title; ?>" />
</body>
