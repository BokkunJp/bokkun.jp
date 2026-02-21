<?php

$mailPath = new \Path(PRIVATE_DIR_LIST['Component']);
$mailPath->setPathEnd();
$mailPath->add('Tag.php');
require_once $mailPath->get();

use Common\Important\UseClass;

function sendMail($header)
{
    if (!isset($header)) {
        return false;
    }

    $allowed = ['to','title','body','from_name','from_address','additional_headers'];

    foreach ($allowed as $key) {
        ${$key} = $header[$key] ?? '';
    }

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

    \ErrorConfig::noErrorMode();         // メール失敗時にはエラーを出さないようにする

    $sendResult = mb_send_mail($to, $title, $body, $addtional_headers, $addtional_parameter);
    if (!$sendResult) {
        $script = new UseClass();
        $script->alert('メールの送信に失敗しました。');
    }

    \ErrorConfig::secureMode();

    return $sendResult;

}
