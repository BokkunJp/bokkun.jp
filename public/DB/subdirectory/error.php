<?php
    define('SELECT_ERROR', '<em>セレクトに失敗しました。</em>');
    define('SAVE_ERROR', '<em>登録に失敗しました。</em>');
    define('UPDATE_ERROR', '<em>更新に失敗しました。</em>');
    define('DELETE_ERROR', '<em>削除に失敗しました。</em>');

    $script = new ScriptClass();
    
    function Validate($id, $val) {
        if (!isset($id) || !isset($fval)) {
            trigger_error('Argument Few.', E_USER_ERROR);
        }

        if (ctype_digit($id)) {
            Error($id. 'is not number.');
        }
    }

    function Error($message) {
        throw($message);
        exit;
    }