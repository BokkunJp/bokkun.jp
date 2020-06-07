<?php
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

$pathList = ['php', 'js', 'css', 'image'];

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
    }
    if (preg_match('/^[a-zA-Z][a-zA-Z+-_]*/', $title) === 0) {
        $adminError->UserError('タイトルに無効な文字が入力されています。');
    } else if (strlen($title) > MAX_LENGTH) {
        $adminError->UserError("タイトルの文字数は、" . MAX_LENGTH . "文字以下にしてください。");
    }
} else if (empty($edit) && isset($delete) &&  $delete === 'delete') {
    // 削除モード
    // $adminError->Confirm('削除してもよろしいですか？');
} else {
    // その他（不正値）
    if (!isset($session['addition'])) {
        $session['addition'] = $post;
        $_SESSION = $session;
    }
    unset($session);
    unset($post);
    $adminError->UserError('不正な値が入力されました。');
}
// $adminError->Maintenance();

chdir($basePath);
foreach ($pathList as $_pathList) {
    if ($_pathList === 'php') {
        chdir("public/");
        if (isset($delete)) {
            // 削除モード

            // 入力値のチェック
            $validate = ValidateData(getcwd(), $select);
            if ($validate === null) {
                $adminError->UserError('ページが選択されていません。');
            } else if ($validate === false) {
                $adminError->UserError('ページの指定が不正です。');
            }

            DeleteData(AddPath(getcwd(), $select));

        } else if (isset($edit)) {
            // 編集モード
        } else {
            // どちらでもない
            $adminError->UserError("不正な遷移です。");
        }
    } else {
        if ($_pathList !== 'php' && empty($client)) {
            $client = "client/";
            $adminPath .= '/' . $client;
        } else {
            $client = "../";
        }
        chdir("{$client}{$_pathList}");               // パスの移動
        if (isset($delete)) {
            // 削除モード
            DeleteData(AddPath(getcwd(), $select));
        } else if (isset($edit)) {
            // 編集モード
        } else {
            // どちらでもない
            $adminError->UserError("不正な遷移です。");
        }
    }
}

if (isset($edit)) {
    $use->Alert('ページを編集しました。');
}else if (isset($delete)) {
    $use->Alert('ページを削除しました。');
}

class AdminError
{
    protected $use;
    public function __construct()
    {
        $this->use = new \PrivateTag\UseClass();
    }

    public function UserError($message, $exit_flg=true)
    {
        $this->use->Alert($message);
        $this->use->BackAdmin('create');
        if ($exit_flg === true) {
            exit;
        }
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