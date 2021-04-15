<?php
use BasicTag\ScriptClass as ScriptClass;

require AddPath(__DIR__, 'Model.php', false);

$posts = PublicSetting\Setting::GetPosts();

if (isset($posts['token'])) {
    $tokenName = 'token';
} else if (isset($posts['searchToken'])) {
    $tokenName = 'searchToken';
}

if (!empty($posts)) {
    Main($posts, $tokenName);
}

function Main($postData, $tokenName) {
    $script = new ScriptClass();
    $sess = new PublicSetting\Session();
    $token = CheckToken($tokenName);
    if ($token === false) {
        // $script->Alert("不正な操作を検知しました。");
        return false;
    }

    $keys = array_keys($postData);

    if ($tokenName === 'token') {
        if (!isset($postData['delete-num']) && !isset($postData['delete-all'])) {
            $ret = InputData($postData['edit-contents']);
            if ($ret) {
                $sess->Write('db-exec', 'コンテンツを保存しました。');
            } else {
                $sess->Write('db-error', 'コンテンツの保存に失敗しました。');
            }
        } else if (isset($postData['delete-all'])) {
            InitializeTable();
            $script->Alert("すべてのデータを削除しました。");
        } else {
            if (is_numeric($postData['edit-id'])) {
                $edit_id = $postData['edit-id'];
                DeleteTable($edit_id);
                $script->Alert("{$edit_id}のデータを削除しました。");
            } else {
                $sess->Write('db-error', '番号の指定が不正です。');
            }
        }

    } else if ($tokenName === 'searchToken') {

    }
}