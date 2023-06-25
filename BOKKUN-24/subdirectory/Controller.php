<?php

use BasicTag\ScriptClass as ScriptClass;

$modelPath = new \Path(__DIR__);
$modelPath->SetPathEnd();
$modelPath->Add('Model.php');
require_once $modelPath->Get();

$posts = \public\Setting::GetPosts();

if (isset($posts['db-input-token'])) {
    $tokenName = 'db-input-token';
} elseif (isset($posts['db-search-token'])) {
    $tokenName = 'db-search-token';
}

if (!class_exists('Public\Token')) {
    $tokenPath = new \Path(PUBLIC_COMMON_DIR);
    $tokenPath->SetPathEnd();
    $tokenPath->Add('Token.php');
    require_once $tokenPath->Get();
}

if (!empty($posts)) {
    Main($posts, $tokenName);
}

function Main($postData, $tokenName)
{
    $script = new ScriptClass();
    $session = new public\Session();
    $token = new \Public\Token($tokenName, $session, true);
    $token->Check();
    if ($token->Check()) {
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
