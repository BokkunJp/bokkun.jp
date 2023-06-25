<?php
$img = "0113.png";

$imagePath = new \Path($base->GetUrl('image', 'client', false), '/');
$imagePath->SetPathEnd();
$imagePath->Add($img);

?>
<header class='header'>
    <div align="left">
        <div>
            <a href="<?= $base->GetUrl('') ?>"><img class='top-image' src="<?= $imagePath->Get() ?>"></a>
            <strong>
                <em>βοκκμη homepage<?= $siteConfig['header']->GetVersion() ?></em> <br />
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