<?php
if ($ua->DesignJudege() === PC_design) {
    echo "<script src='//code.jquery.com/jquery-3.4.0.min.js'></script>";
    echo "<script src='//code.jquery.com/ui/1.12.0/jquery-ui.min.js' integrity='sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E='crossorigin='anonymous'></script>";
    echo "<script src='https: //cdn.jsdelivr.net/npm/vue'></script>";
} else if ($ua->DesignJudege() === SP_design) {
    echo "<script src='//code.jquery.com/jquery-3.4.0.min.js'></script>";
    echo "<script src='//code.jquery.com/ui/1.12.0/jquery-ui.min.js' integrity='sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E='crossorigin='anonymous'></script>";
    echo "<script src='https: //cdn.jsdelivr.net/npm/vue'></script>";
}
