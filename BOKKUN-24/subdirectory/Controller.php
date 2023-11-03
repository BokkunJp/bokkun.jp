<?php

use Public\Important\ScriptClass as ScriptClass;

$modelPath = new \Path(__DIR__);
$modelPath->setPathEnd();
$modelPath->add('Model.php');
require_once $modelPath->get();

$posts = \Public\Important\Setting::getPosts();

if (isset($posts['db-input-token'])) {
    $tokenName = 'db-input-token';
} elseif (isset($posts['db-search-token'])) {
    $tokenName = 'db-search-token';
}

if (!class_exists('Public\Important\Token')) {
    $tokenPath = new \Path(PUBLIC_COMMON_DIR);
    $tokenPath->setPathEnd();
    $tokenPath->add('Token.php');
    require_once $tokenPath->get();
}

if (!empty($posts)) {
    main($posts, $tokenName);
}

function main($postData, $tokenName)
{
    $script = new ScriptClass();
    $session = new Public\Important\Session();
    $token = new \Public\Important\Token($tokenName, $session, true);
    $token->check();
    if ($token->check()) {
        // $script->alert("不正な操作を検知しました。");
        return false;
    }

    $keys = array_keys($postData);

    if ($tokenName === 'token') {
        if (!isset($postData['delete-num']) && !isset($postData['delete-all'])) {
            $ret = inputData($postData['edit-contents']);
            if ($ret) {
                $session->write('db-exec', 'コンテンツを保存しました。');
            } else {
                $session->write('db-error', 'コンテンツの保存に失敗しました。');
            }
        } elseif (isset($postData['delete-all'])) {
            initializeTable();
            $script->alert("すべてのデータを削除しました。");
        } else {
            if (is_numeric($postData['edit-id'])) {
                $edit_id = $postData['edit-id'];
                deleteTable($edit_id);
                $script->alert("{$edit_id}のデータを削除しました。");
            } else {
                $session->write('db-error', '番号の指定が不正です。');
            }
        }
    } elseif ($tokenName === 'searchToken') {
    }
}
