<?php
function CSRFErrorMessage() {
    $errMessage = "<p><strong>". gethostbyaddr(PublicSetting\GetSERVER('REMOTE_ADDR')). "(". PublicSetting\GetSERVER('REMOTE_ADDR'). ")". "様のアクセスは禁止されています。</strong></p><p>以下の要因が考えられます。</p>";
    $errList = ["指定回数以上アクセスした。", "直接アクセスした。", "不正アクセスした。"];
    $errMessage .='<ul>';
    $errLists = '';
    foreach ($errList as $_errList) {
        $errLists .= "<li>$_errList</li>";
    }
    $errMessage .= $errLists;
    $errMessage .='</ul>';    

    return $errMessage;
}


