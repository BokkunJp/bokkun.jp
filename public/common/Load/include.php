<?php
if (isset($ua)) {
    if ($ua->DesignJudge() === PC_design) {
        echo "<script src='//code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>";
        echo "<script src='//code.jquery.com/ui/1.13.1/jquery-ui.min.js' integrity='sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=' crossorigin='anonymous'></script>";

        if ($homepageTitle === 'OpenCV') {
            echo "<script src='//docs.opencv.org/5.x/opencv.js'></script>";
        }

        if (PublicSetting\Setting::GetServerName() === 'bokkun.jp.local') {
            echo "<script src='//jp.vuejs.org/js/vue.js'></script>";
        } else {
            echo "<script src='//jp.vuejs.org/js/vue.min.js'></script>";
        }
    } elseif ($ua->DesignJudge() === SP_design) {
        echo "<script src='//code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>";
        echo "<script src='//code.jquery.com/ui/1.13.1/jquery-ui.min.js' integrity='sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=' crossorigin='anonymous'></script>";

        if ($homepageTitle === 'OpenCV') {
            echo "<script src='//docs.opencv.org/5.x/opencv.js'></script>";
        }

        if (PublicSetting\Setting::GetServerName() === 'bokkun.jp.local') {
            echo "<script src='//jp.vuejs.org/js/vue.js'></script>";
        } else {
            echo "<script src='//jp.vuejs.org/js/vue.min.js'></script>";
        }
    }
}
