<?php
$img = "0113.png";

$imagePath = new \Path($base->getUrl('client', 'image'), '/');
$imagePath->setPathEnd();
$imagePath->add($img);

?>
<header class='header'>
    <div align="left">
        <div>
            <a href="<?= $base->getUrl('root') ?>"><img class='top-image' src="<?= $imagePath->get() ?>"></a>
            <strong>
                <em>βοκκμη homepage<?= $siteConfig['header']->getVersion() ?></em> <br />
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