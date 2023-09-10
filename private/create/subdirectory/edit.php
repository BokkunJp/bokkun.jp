<?php
define("DS", DIRECTORY_SEPARATOR);
define("MAX_LENGTH", 32);

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
    ]
);

// パスの追加
// 定数・固定文言など
$privatepathList->ResetKey('word');
$privatepathList->MethodPath('AddArray', ['common', 'Word', 'Message.php']);

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

// パスの出力
$privatepathList->All();
foreach ($privatepathList->Get() as $path) {
    require_once $path;
}

$session =  new \private\Session();
$adminError = new AdminError();
$use = new \PrivateTag\UseClass();

// tokenチェック
$editToken = new \private\Token("edit-token", $session);

// 不正tokenの場合は、エラーを出力して処理を中断。
if ($editToken->Check() === false) {
    $sessClass =  new private\Session();
    $sessClass->Write('notice', '<span class="warning">不正な遷移です。もう一度操作してください。</span>', 'Delete');
    $url = new private\Setting();
    $backUrl = CreateClient('private', dirname(__DIR__));
    $backUrl = ltrim($backUrl, DS);
    header('Location:' . $url->GetUrl($backUrl));
    exit;
}

$adminPath = dirname(__DIR__);
$basePath = dirname(__DIR__, 3);

$post = (array)private\Setting::GetPosts();
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

$pathList = ['php'];
$clientPath = new \Path($basePath);
$clientPath->Add('public');
$clientPath->Add('client');
$clientPath = $clientPath->Get();
$subPathList = scandir($clientPath);
foreach ($subPathList as $_key => $_val) {
    if (!FindFileName($_val)) {
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
            $copyPath->Add($select);
            CopyData($copyPath->Get(), $copy_title);
        } elseif (isset($edit)) {
            // 編集モード
        } else {
            // どちらでもない
            $adminError->UserError("不正な遷移です。");
        }
    } else {
        $cwd = new \Path('');
        $cwd->Add($select);
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
                DeleteData(getcwd(), $cwd->Get());
            }
        } elseif (isset($copy)) {
            // 複製モード
            if (!empty($select) && is_dir($cwd->Get())) {
                CopyData($cwd->Get(), $copy_title);
            }
        } elseif (isset($edit)) {
            // 編集モード
        } else {
            // どちらでもない
            $adminError->UserError("不正な遷移です。");
        }
        $cwd = new \Path(dirname($cwd->Get()));
    }
}

if (isset($edit)) {
    $use->Alert("{$select}ページを編集しました。");
} elseif (isset($copy)) {
    $use->Alert("{$select}ページを複製しました。");
} elseif (isset($delete)) {
    if (!isset($notDelflg)) {
        $use->Alert("{$select}ページを削除しました。");
    } else {
        $use->Alert("{$select}ページは削除できません。");
    }
}

class AdminError
{
    protected $use;
    public function __construct(?\PrivateTag\UseClass $use = null)
    {
        if (!is_null($use)) {
            $this->use = $use;
        } else {
            $this->use = new \PrivateTag\UseClass();
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
        $this->use->Alert($message);
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
    public function Alert(string $message)
    {
        $this->use->Alert($message);
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
    public function Confirm(string $message)
    {
        $this->use->Confirm($message);
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