<?php
$imgUrl = $url;
if (strpos(basename(getcwd()), 'private') === false) {
    $urlPath = new \Path($url);
    $urlPath->Add('private');
    $url = $urlPath->Get();
}
?>
<header class='header'>
    <div align="left">
        <?php $img = "pc15.gif"; ?>
        <div>
            <a href="<?php echo $url; ?>"><img
                    src="<?php echo $imgUrl; ?>/private/client/image/<?php echo $img; ?>"
                    width="40" height="40"></a>
            <strong>
                <em>βοκκμη homepage<?= $siteConfig['header']->GetVersion() ?></em>
                <br />
                <div align="center"><?php if (isset($title)) {
    echo $title;
} else {
    echo 'デフォルトタイトル';
} ?>
                </div>
            </strong>
        </div>
    </div>

    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr class="top_hr" />
</header>