<?php

$mailPath = new \Path(PRIVATE_COMPONENT_DIR);
$mailPath->setPathEnd();
$mailPath->add('Tag.php');
require_once $mailPath->get();

use Private\Important\ScriptClass;

function sendMail($header, $response=false)
{
    if (!isset($header)) {
        return false;
    }
    list($to, $title, $body, $from_name, $from_address) = $header;

    // 差出人名文字化け回避用
    $from_name = mb_encode_mimeheader($from_name);
    $addtional_headers ="From:{$from_name}<{$from_address}>";

    if (!isset($to) || empty($to)) {
        echo '入力が不正です。';
        return false;
    }

    if (!isset($title) || empty($title)) {
        $title = '';
    }

    if (!isset($body) || empty($body)) {
        $body = '';
    }

    if (!isset($addtional_headers) || empty($addtional_headers)) {
        $addtional_headers = '';
    }

    if (!isset($addtional_parameter) || empty($addtional_parameter)) {
        $addtional_parameter = '';
    }


    if (mb_send_mail($to, $title, $body, $addtional_headers, $addtional_parameter)) {
        $ret = true;
    } else {
        $ret = false;
        $script = new ScriptClass();
        $script->alert('メールの送信に失敗しました。');
    }

    if ($response === true) {
        return $ret;
    }
}
