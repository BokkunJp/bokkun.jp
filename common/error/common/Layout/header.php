<header class='header'>
    <div align="left">
    <?php $img="0113.png"; ?>
        <div>
            <a href="<?=$base->GetURL()?>"><img class='top-image' src="<?=$base->GetURL('')?>common/error/client/image/<?=$img?>"></a>
            <link rel="shortcut icon" href="<?=$base->GetURL('')?>common/error/client/image/IMG_7592.PNG">
            <strong>
                <em>βοκκμη homepage<?= $siteConfig['header']->getVersion() ?></em> <br/>
                <div align="center">
<?php if (isset($title)) {
    echo $title;
} else {
    echo 'ぼっくんのホームページ';
} ?>
                </div>
            </strong>
        </div>
    </div>
    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr />
</header>
