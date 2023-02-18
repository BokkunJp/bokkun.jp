<?php

use BasicTag\ScriptClass as ScriptClass;

require_once AddPath(__DIR__, 'Model.php', false);

$posts = \public\Setting::GetPosts();

if (isset($posts['db-input-token'])) {
    $tokenName = 'db-input-token';
} elseif (isset($posts['db-search-token'])) {
    $tokenName = 'db-search-token';
}

if (!class_exists('Public\Token')) {
    require_once AddPath(PUBLIC_COMMON_DIR, 'Token.php', false);
}

if (!empty($posts)) {
    Main($posts, $tokenName);
}

function Main($postData, $tokenName)
{
    $script = new ScriptClass();
    $session = new public\Session();
    $token = new \Public\Token($tokenName, $session, true);
    $token->CheckToken();
    if ($token->CheckToken()) {
        // $script->Alert("不正な操作を検知しました。");
        return false;
    }

    $keys = array_keys($postData);

    if ($tokenName === 'token') {
        if (!isset($postData['delete-num']) && !isset($postData['delete-all'])) {
            $ret = InputData($postData['edit-contents']);
            if ($ret) {
                $session->Write('db-exec', 'コンテンツを保存しました。');
            } else {
                $session->Write('db-error', 'コンテンツの保存に失敗しました。');
            }
        } elseif (isset($postData['delete-all'])) {
            InitializeTable();
            $script->Alert("すべてのデータを削除しました。");
        } else {
            if (is_numeric($postData['edit-id'])) {
                $edit_id = $postData['edit-id'];
                DeleteTable($edit_id);
                $script->Alert("{$edit_id}のデータを削除しました。");
            } else {
                $session->Write('db-error', '番号の指定が不正です。');
            }
        }
    } elseif ($tokenName === 'searchToken') {
    }
}
