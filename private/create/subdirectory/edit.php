<?php
define("DS", DIRECTORY_SEPARATOR);
define("MAX_LENGTH", 32);

// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DS . 'common' . DS . 'InitFunction.php';

// パスの初期セット
$privatepathList = new PathApplication('word', dirname(__DIR__, 2));

// それぞれの変数セット
$privatepathList->setAll(
    [
        'setting' => '',
        'include' => '',
        'session' => '',
        'token' => '',
    ]
);

// パスの追加
// 定数・固定文言など
$privatepathList->resetKey('word');
$privatepathList->methodPath('AddArray', ['common', 'Word', 'Message.php']);

// 設定
$privatepathList->resetKey('setting');
$privatepathList->methodPath('AddArray', ['common', 'Setting.php']);

// セッション
$privatepathList->resetKey('session');
$privatepathList->methodPath('AddArray', ['common', 'Session.php']);

// トークン
$privatepathList->resetKey('token');
$privatepathList->methodPath('AddArray', ['common', 'Token.php']);

// ファイル読み込み
$privatepathList->resetKey('include');
$privatepathList->methodPath('AddArray', ['common', 'Include.php']);

// パスの出力
$privatepathList->all();
foreach ($privatepathList->get() as $path) {
    require_once $path;
}

$session =  new \Private\Important\Session();
$adminError = new AdminError();
$use = new \Private\Important\UseClass();

// tokenチェック
$editToken = new \Private\Important\Token("edit-token", $session);

// 不正tokenの場合は、エラーを出力して処理を中断。
if ($editToken->check() === false) {
    $sessClass =  new Private\Important\Session();
    $sessClass->write('notice', '<span class="warning">不正な遷移です。もう一度操作してください。</span>', 'Delete');
    $url = new Private\Important\Setting();
    $backUrl = createClient('private', dirname(__DIR__));
    $backUrl = ltrim($backUrl, DS);
    header('Location:' . $url->getUrl($backUrl));
    exit;
}

$adminPath = dirname(__DIR__);
$basePath = dirname(__DIR__, 3);

$post = (array)Private\Important\Setting::getPosts();
$judge = array();
foreach ($post as $post_key => $post_value) {
    $$post_key = $post_value;
    $judge[$$post_key] = $post_value;
}

// 内容をセッションに保存し、不要なデータを破棄
if (!$session->judgeArray('admin', 'addition')) {
    $session->writeArray('admin', 'addition', $post);
}
unset($session);
unset($post);

$pathList = ['php'];
$clientPath = new \Path($basePath);
$clientPath->add('public');
$clientPath->add('client');
$clientPath = $clientPath->get();
$subPathList = scandir($clientPath);
foreach ($subPathList as $_key => $_val) {
    if (!findFileName($_val)) {
        unset($subPathList[$_key]);
    }
}
$pathList = array_merge($pathList, $subPathList);

if ((isset($edit) || isset($copy)) && empty($delete)) {
    // 編集モード
    if (empty($copy_title)) {
        $adminError->UserError('未記入の項目があります。');
    } else {
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
                    if (isset($edit) && !isset($delete)) {
                        if ($file === $copy_title) {
                            $adminError->UserError("ご指定のタイトルのファイルは既に存在します。ページの編集を中止します。");
                        }
                    }
                }
            }
        }
    }
    if (preg_match('/^[a-zA-Z][a-zA-Z0-9-_+]*$/', $copy_title) === 0) {
        $adminError->UserError('タイトルに無効な文字が入力されています。');
    } elseif (strlen($copy_title) > MAX_LENGTH) {
        $adminError->UserError("タイトルの文字数は、" . MAX_LENGTH . "文字以下にしてください。");
    }
} elseif (empty($delete)) {
    // その他（不正値）
    if (!isset($session['addition'])) {
        $session['addition'] = $post;
        $_SESSION = $session;
    }
    unset($session);
    unset($post);
    $adminError->UserError('不正な値が入力されました。');
}

chdir($basePath);

// 入力値のチェック
if (!isset($select)) {
    $select = '';
}
$validate = ValidateData(getcwd(), $select);
if ($validate === null) {
    $adminError->UserError('ページが選択されていません。');
} elseif ($validate === false) {
    $adminError->UserError('ページの指定が不正です。');
}

// 削除不可判定
$notList = GetNotDelFileList();
foreach ($notList as $_nList) {
    if ($_nList === $select) {
        $notDelflg = true;
        break;
    }
}

foreach ($pathList as $_pathList) {
    if ($_pathList === 'php') {
        if (isset($delete)) {
            // 削除モード
            if (!isset($notDelflg)) {
                DeleteData($basePath, $select);
            }
        } elseif (isset($copy)) {
            // 複製モード
            $copyPath = new \Path($basePath);
            $copyPath->add($select);
            CopyData($copyPath->get(), $copy_title);
        } elseif (isset($edit)) {
            // 編集モード
        } else {
            // どちらでもない
            $adminError->UserError("不正な遷移です。");
        }
    } else {
        $cwd = new \Path('');
        $cwd->add($select);
        if (!strpos(getcwd(), 'client')) {
            $client = "public/client/";
            $adminPath = dirname($adminPath). '/Sample/' . $client;
        } else {
            $client = "../";
        }
        chdir("{$client}{$_pathList}");               // パスの移動
        if (isset($delete)) {
            // 削除モード
            if (!isset($notDelflg)) {
                DeleteData(getcwd(), $cwd->get());
            }
        } elseif (isset($copy)) {
            // 複製モード
            if (!empty($select) && is_dir($cwd->get())) {
                CopyData($cwd->get(), $copy_title);
            }
        } elseif (isset($edit)) {
            // 編集モード
        } else {
            // どちらでもない
            $adminError->UserError("不正な遷移です。");
        }
        $cwd = new \Path(dirname($cwd->get()));
    }
}

if (isset($edit)) {
    $use->alert("{$select}ページを編集しました。");
} elseif (isset($copy)) {
    $use->alert("{$select}ページを複製しました。");
} elseif (isset($delete)) {
    if (!isset($notDelflg)) {
        $use->alert("{$select}ページを削除しました。");
    } else {
        $use->alert("{$select}ページは削除できません。");
    }
}

class AdminError
{
    protected $use;
    public function __construct(?\Private\Important\UseClass $use = null)
    {
        if (!is_null($use)) {
            $this->use = $use;
        } else {
            $this->use = new \Private\Important\UseClass();
        }
    }

    /**
     * UserError
     *
     * エラー文言を設定
     *
     * @param string $message
     * @param boolean $exit_flg
     *
     * @return void
     */
    public function UserError(string $message, bool $exit_flg = true)
    {
        $this->use->alert($message);
        $this->use->BackAdmin('create');
        if ($exit_flg === true) {
            exit;
        }
    }

    /**
     * Alert
     *
     * アラート出力
     *
     * @param string $message
     *
     * @return void
     */
    public function alert(string $message)
    {
        $this->use->alert($message);
    }

    /**
     * Confirm
     *
     * コンフォーム出力
     *
     * @param string $message
     *
     * @return void
     */
    public function confirm(string $message)
    {
        $this->use->confirm($message);
    }

    /**
     * Maintenance
     *
     * メンテナンス表示
     *
     * @return void
     */
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
        value="<?php echo $copy_title; ?>" />
</body>