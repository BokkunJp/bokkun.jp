<?php
if (!isset($ajaxFlg)) {
    echo "<script src='https://code.jquery.com/jquery-3.7.1.min.js' ></script>";
    echo "<script src='https://code.jquery.com/ui/1.13.3/jquery-ui.min.js' ></script>";
    if (Private\Important\Setting::getServerName() === 'bokkun.jp.local') {
        // echo "<script src='//unpkg.com/vue@3.2.47/dist/vue.global.js'></script>";
    } else {
        // echo "<script src='//jp.vuejs.org/js/vue.min.js'></script>";
    }
}