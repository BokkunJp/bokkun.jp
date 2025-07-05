<?php
use Common\Important\ScriptClass as ScriptClass;

require_once 'Model.php';
require_once PUBLIC_COMMON_DIR . '/Token.php';

$script = new ScriptClass();
$posts = Public\Important\Setting::getPosts();

// セッションセット
$session = new Public\Important\Session('mail');

$postData = $posts;
$nulFlg = false;
if (is_array($posts)) {
    foreach (array_keys($posts) as $_val) {
        if (!preg_match("/(.*)token(.*)/", $_val)) {
            if (empty($posts[$_val])) {
                $nulFlg = true;
            }
        }
    }
}

// Tokenクラスをセット
$publicMailToken = new Public\Important\Token('public-mail-token', $session, true);

if (isset($posts) && !empty($posts) && $nulFlg === false) {
    if ($publicMailToken->check() === false) {
        $script->alert("不正な値が送信されました。");
    } else {
        $mailSession = $session->read("mail");

        if (isset($mailSession['send_date'])) {
            $script->alert("本日はもうメール送信できません。");

            // 日付が変わるかメールを送信してから1日経ったら、再送信可能とする
            $sendDateInterval = $mailSession['send_date']->diff(new DateTime());
            if (diffDate($mailSession['send_date'], new DateTime())->format('%d') >= DENY_SEND_DATE
                || (new DateTime())->diff($mailSession['send_date'])->format('%d') >= DENY_SEND_DATE
            ) {
                $session->delete("send_date");
            }

            return;
        } else {
            sendMail(['private.mail@bokkun.jp', $posts['title'], $posts['body'], 'βοκκμη', 'from.mail@bokkun.jp']);
            $script->alert("メールを送信しました。");

            // 送信日時をセット
            $session->writeArray("mail", "send_date", new DateTime());
        }
    }
} else {
    // $session->writeArray('mail', 'error-message', '送信内容に不備があります。');
}

/**
 * diffDate
 * 時間帯を無視して、日付単位で差分を取得する
 *
 * @param \DateTime $dateTime1 対象の日時(前)
 * @param \DateTime $dateTime2 対象の時間帯(後)
 * @return DateInterval|false
 */
function diffDate(\DateTime $dateTime1, \DateTime $dateTime2): DateInterval|false
{
    $date1 = new DateTime($dateTime1->format('Y-m-d'));
    $date2 = new DateTime($dateTime2->format('Y-m-d'));

    return $date1->diff($date2);
}