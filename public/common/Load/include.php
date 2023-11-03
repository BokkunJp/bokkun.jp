<?php
if (isset($ua)) {
    if ($ua->judgeDevice() === PC_design) {
        echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>";
        echo "<script src='https://code.jquery.com/ui/1.13.1/jquery-ui.min.js' integrity='sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=' crossorigin='anonymous'></script>";

        if (isset($homepageTitle) && $homepageTitle === 'OpenCV') {
            echo "<script src='https://docs.opencv.org/5.x/opencv.js'></script>";
        }

        echo "<script src='//unpkg.com/vue@3.2.47/dist/vue.global.js'></script>";
    } elseif ($ua->judgeDevice() === SP_design) {
        echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js' integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=' crossorigin='anonymous'></script>";
        echo "<script src='https://code.jquery.com/ui/1.13.1/jquery-ui.min.js' integrity='sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=' crossorigin='anonymous'></script>";

        if ($homepageTitle === 'OpenCV') {
            echo "<script src='https://docs.opencv.org/5.x/opencv.js'></script>";
        }

        echo "<script src='https://unpkg.com/vue@next'></script>";
    }
}
