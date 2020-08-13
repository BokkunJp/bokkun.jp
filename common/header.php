<?php
$img = "0113.png";
?>
<header class='header'>
    <div align="left">
        <div>
            <a href="<?= $base->GetURL('') ?>"><img class='top-image' src="<?= AddPath($base->GetURL('image', 'client', false), $img, false, '/') ?>"></a>
            <strong>
                <em>Bokkun's homepage<?= $siteConfig['header']->GetVersion() ?></em> <br />
                <div class="top" align="center"><?php if (isset($title)) {
                                                    echo $title;
                                                } else {
                                                    echo 'ぼっくんのホームページ';
                                                } ?></div>
            </strong>
        </div>
    </div>
    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr class="top_hr" />
</header>