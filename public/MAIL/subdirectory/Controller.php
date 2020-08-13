<?php

use BasicTag\ScriptClass as ScriptClass;

require_once 'Model.php';
require_once PUBLIC_COMMON_DIR . '/Token.php';

$script = new ScriptClass();
$posts = PublicSetting\Setting::getPosts();

$valid = true;

if (isset($posts) && !empty($posts)) {
    $token = CheckToken();

    if ($token === false) {
        $script->Alert("不正な値が送信されました。");
    } else {
        $session = $_SESSION;

        if (isset($session['mail']['send_flg']) && $session['mail']['send_flg'] === true) {
            $closed = CheckToken('closed
        ');

            if ($closed === false) {
                $script->Alert("本日はもうメール送信できません。");
                $valid = false;
            }
        }

        $session['mail']['send_flg'] = true;
        $_SESSION = $session;

        if ($valid === true) {
            SendMail(['private.mail@bokkun.jp', $posts['title'], $posts['body'], 'ぼっくん', 'from.mail@bokkun.jp']);
            $script->Alert("メールを送信しました。");
        }
    }
}
