<?php

echo "<script src='https://code.jquery.com/jquery-3.7.1.min.js' ></script>";
echo "<script src='https://code.jquery.com/ui/1.14.1/jquery-ui.min.js' ></script>";

if (isset($homepageTitle) && $homepageTitle === 'OpenCV') {
    echo "<script src='https://docs.opencv.org/5.x/opencv.js'></script>";
}

echo "<script src='https://unpkg.com/vue@next'></script>";
