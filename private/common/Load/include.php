<?php
    echo "<script src='https://code.jquery.com/jquery-3.7.1.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>";
    echo "<script src='https://code.jquery.com/ui/1.13.3/jquery-ui.min.js' integrity='sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=' crossorigin='anonymous'></script>";
    if (Private\Important\Setting::getServerName() === 'bokkun.jp.local') {
        // echo "<script src='//unpkg.com/vue@3.2.47/dist/vue.global.js'></script>";
    } else {
        // echo "<script src='//jp.vuejs.org/js/vue.min.js'></script>";
    }
