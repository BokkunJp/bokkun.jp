<header class='header'>
    <div align="left">
        <?php $img = "pc15.gif"; ?>
        <div>
            <a href="<?php echo $url; ?>"><img src="<?php echo $url; ?>/private/client/image/<?php echo $img; ?>" width="40" height="40"></a>
            <link rel="shortcut icon" href="<?php echo $url; ?>/private/client/image/5959715.png">
            <strong>
                <em>Bokkun's homepage<?= $siteConfig['header']->GetVersion() ?></em> <br />
                <div align="center"><?php if (isset($title)) {
                                        echo $title;
                                    } else {
                                        echo 'デフォルトタイトル';
                                    } ?></div>
            </strong>
        </div>
    </div>

    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr class="top_hr" />
</header>