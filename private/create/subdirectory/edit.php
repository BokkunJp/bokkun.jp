<?php
define("DS", DIRECTORY_SEPARATOR);
define("MAX_LENGTH", 32);

// 関数定義 (初期処理用)
require_once dirname(__DIR__, 2) . DS . 'common' . DS . 'InitFunction.php';
require_once __DIR__. DS .'Component'. DS. 'adminClass.php';

// パスの初期セット
$privatepathList = new PathApplication('word', dirname(__DIR__, 2));

// それぞれの変数セット
$privatepathList->setAll(
    [
        'setting' => '',
        'include' => '',
        'session' => '',
        'token' => '',
        'cache' => '',
        'common' => '',
        'ua' => '',
        'config' => dirname(__DIR__, 3),
    ]
);

// パスの追加
// 管理側共通(ログイン認証など)
$privatepathList->setKey('common');
$privatepathList->methodPath('addArray', ['common.php']);

// 定数・固定文言など
$privatepathList->setKey('word');
$privatepathList->methodPath('addArray', ['common', 'Word', 'Message.php']);

// UA
$privatepathList->setKey('ua');
$privatepathList->methodPath('addArray', ['common', 'Component', 'Ua.php']);

$privateList = [
    'config' => 'Config.php',
    'setting' => 'Setting.php',
    'session' => 'Session.php',
    'token' => 'Token.php',
    'cache' => 'Cache.php',
    'include' => 'Include.php',
];

// ヘッダー・フッター
// 設定
// セッション
// トークン
// キャッシュ
// ファイル読み込み
foreach ($privateList as $key => $file) {
    $privatepathList->setKey($key);
    $privatepathList->methodPath('addArray', ['common', $file]);
}

// パスの出力
$privatepathList->resetKey();
foreach ($privatepathList->get() as $path) {
    require_once $path;
}

$session =  new \Private\Important\Session('create-page');
$admin = new \Private\Important\adminClass();

// tokenチェック
$editToken = new \Common\Important\Token("edit-token", $session);

// 不正tokenの場合は、エラーを出力して処理を中断。
if ($editToken->check() === false) {;
    $session->write('notice', '<span class="warning">不正な遷移です。もう一度操作してください。</span>', 'Delete');
    $url = new Private\Important\Setting();
    $backUrl = createClient('private', dirname(__DIR__));
    $backUrl = ltrim($backUrl, DS);
    header('Location:' . $url->getUrl('root', $backUrl));
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
if (!$session->judge('addition')) {
    $session->write('addition', $post);
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
        $admin->alertError('未記入の項目があります。');
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
                            $admin->alertError("ご指定のタイトルのファイルは既に存在します。ページの編集を中止します。");
                        }
                    }
                }
            }
        }
    }
    if (preg_match('/^[a-zA-Z][a-zA-Z0-9-_+]*$/', $copy_title) === 0) {
        $admin->alertError('タイトルに無効な文字が入力されています。');
    } elseif (strlen($copy_title) > MAX_LENGTH) {
        $admin->alertError("タイトルの文字数は、" . MAX_LENGTH . "文字以下にしてください。");
    }
} elseif (empty($delete)) {
    // その他（不正値）
    if (!isset($session['addition'])) {
        $session['addition'] = $post;
        $_SESSION = $session;
    }
    unset($session);
    unset($post);
    $admin->alertError('不正な値が入力されました。');
}

chdir($basePath);

// 入力値のチェック
if (!isset($select)) {
    $select = '';
}
$validate = validateData(getcwd(), $select);
if ($validate === null) {
    $admin->alertError('ページが選択されていません。');
} elseif ($validate === false) {
    $admin->alertError('ページの指定が不正です。');
}

// 削除不可判定
$notList = getNotDelFileList();
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
                deleteData($basePath, $select);
            }
        } elseif (isset($copy)) {
            // 複製モード
            $copyPath = new \Path($basePath);
            $copyPath->add($select);
            copyData($copyPath->get(), $copy_title);
        } elseif (isset($edit)) {
            // 編集モード
        } else {
            // どちらでもない
            $admin->alertError("不正な遷移です。");
        }
    } else {
        $cwd = new \Path('');
        $cwd->add($select);
        if (!strpos(getcwd(), 'client')) {
            $client = "public/client/";
            $adminPath = dirname($adminPath). '/sample/' . $client;
        } else {
            $client = "../";
        }
        chdir("{$client}{$_pathList}");               // パスの移動
        if (isset($delete)) {
            // 削除モード
            if (!isset($notDelflg)) {
                deleteData(getcwd(), $cwd->get());
            }
        } elseif (isset($copy)) {
            // 複製モード
            if (!empty($select) && is_dir($select)) {
                copyData($select, $copy_title);
            }
        } elseif (isset($edit)) {
            // 編集モード
        } else {
            // どちらでもない
            $admin->alertError("不正な遷移です。");
        }
        $cwd = new \Path(dirname($cwd->get()));
    }
}

if (isset($edit)) {
    $admin->alert("{$select}ページを編集しました。");
} elseif (isset($copy)) {
    $admin->alert("{$select}ページを複製しました。");
} elseif (isset($delete)) {
    if (!isset($notDelflg)) {
        $admin->alert("{$select}ページを削除しました。");
    } else {
        $admin->alert("{$select}ページは削除できません。");
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