<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!isset($_SESSION)) {
    session_start();
}
require_once dirname(dirname(__DIR__)) . '/common/Component/Tag.php';
define("MAX_LENGTH", 32);

$adminError = new AdminError();
$use = new \PrivateTag\UseClass();

$adminPath = dirname(__DIR__);
$basePath = dirname(dirname(dirname(__DIR__)));

$session = $_SESSION;
$post = $_POST;
$judge = array();
foreach ($post as $post_key => $post_value) {
    $$post_key = $post_value;
    $judge[$$post_key] = $post_value;
}

if (isset($edit) && $edit === 'edit' && empty($delete)) {
    // 編集モード
    if (empty($title)) {
        if (!isset($session['addition'])) {
            $session['addition'] = $post;
            $_SESSION = $session;
        }
        unset($session);
        unset($post);
        echo $title;
        $adminError->UserError('未記入の項目があります。');
    } else {
        $pathList = ['php', 'js', 'css', 'image'];
        // ファイル存在チェック
        foreach ($pathList as $_pathList) {
            $client = $basePath . '/public/';
            if ($_pathList !== 'php') {
                $client .= "client/$_pathList/";
            }
            foreach (scandir($client) as $file) {
                if (mb_strpos($file, '.') !== 0) {
                    if (isset($edit) && !isset($delete)) {
                        if ($file === $title) {
                            $adminError->UserError("ご指定のタイトルのファイルは既に存在します。ページの編集を中止します。");
                        }
                    }
                }
            }
        }
        $mode = 'edit';
    }
    if (preg_match('/^[a-zA-Z][a-zA-Z+-_]*/', $title) === 0) {
        $adminError->UserError('タイトルに無効な文字が入力されています。');
    } else if (strlen($title) > MAX_LENGTH) {
        $adminError->UserError("タイトルの文字数は、" . MAX_LENGTH . "文字以下にしてください。");
    }
} else if (empty($edit) && isset($delete) &&  $delete === 'delete') {
    // 削除モード
    $adminError->Confirm('削除してもよろしいですか？');
    $mode = 'delete';
} else {
    // その他（不正値）
    if (!isset($session['addition'])) {
        $session['addition'] = $post;
        $_SESSION = $session;
    }
    var_dump($edit);
    die;
    unset($session);
    unset($post);
    $adminError->UserError('不正な値が入力されました。');
}
$adminError->Maintenance();

chdir($basePath);
// フォルダ・ファイル名の変更
foreach ($pathList as $_pathList) {
    if ($_pathList === 'php') {
        chdir("public/");
    } else {
        if ($_pathList === 'js') {
            $client = "client/";
            $adminPath .= '/' . $client;
        } else {
            $client = "../";
        }
        chdir("$client $_pathList");               // パスの移動
    }
    // ファイル名変更の場合


    // ファイル削除の場合
    // mkdir($title);
    // if (file_exists("$title") === false) {         // ディレクトリ作成
    //     $$adminError->UserError('ページの作成に失敗しました。');
    // }

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
        var_dump("/$baseFileName/design.php");
        // copy("$baseFileName/design.php", "$title/design.php");          // フォルダ内のdesgin.php作成
        if ($use_smarty === 'on') {
            // copy("$baseFileName/index.tpl", "$title/index.tpl");        // smarty設定時、index.tpl作成
        } else {
            // mkdir("$title/subdirectory");                               // smarty未設定時、subdirectoryディレクトリ作成
        }
    }
}
$use->Alert('ページを作成しました。');
session_destroy();

class AdminError
{
    protected $use;
    public function __construct()
    {
        $this->use = new \PrivateTag\UseClass();
    }

    public function UserError($message)
    {
        $this->use->Alert($message);
        $this->use->BackAdmin('create');
        exit;
    }

    public function Alert($message)
    {
        $this->use->Alert($message);
    }

    public function Confirm($message)
    {
        $this->use->Confirm($message);
    }
    public function Maintenance()
    {
        $this->UserError('メンテナンス中です。しばらくお待ちください。');
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
            title = location.protocol + '//' + location.host + '/public/' + title;
            open(title);
        }

        location.href = "./";
    }
</script>

<body>
    <input type="hidden" name="title" value="<?php echo $title; ?>" />
</body>