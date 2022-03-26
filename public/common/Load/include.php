<?php

if ($ua->DesignJudge() === PC_design) {
    echo "<script src='//code.jquery.com/jquery-3.6.0.min.js'></script>";
    echo "<script src='//code.jquery.com/ui/1.13.0/jquery-ui.min.js' integrity='sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E='crossorigin='anonymous'></script>";
    if (PublicSetting\Setting::GetServarName() === 'bokkun.jp.local') {
        echo "<script src='//jp.vuejs.org/js/vue.js'></script>";
    } else {
        echo "<script src='//jp.vuejs.org/js/vue.min.js'></script>";
    }
} elseif ($ua->DesignJudge() === SP_design) {
    echo "<script src='//code.jquery.com/jquery-3.6.0.min.js'></script>";
    echo "<script src='//code.jquery.com/ui/1.13.0/jquery-ui.min.js' integrity='sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E='crossorigin='anonymous'></script>";
    if (PublicSetting\Setting::GetServarName() === 'bokkun.jp.local') {
        echo "<script src='//jp.vuejs.org/js/vue.js'></script>";
    } else {
        echo "<script src='//jp.vuejs.org/js/vue.min.js'></script>";
    }
}
