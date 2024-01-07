<header class='header'>
    <div align="left">
        <?php $img="crown-vector.jpg"; ?>
        <div>
            <a href="<?php echo $url; ?>"><img src="<?= $base->getUrl('image') ?>/<?php echo $img; ?>" width="40" height="40"></a>
            <strong>
                <em>βοκκμη homepage<?= $siteConfig['header']->getVersion() ?></em> <br/>
                <div class="top" align="center"><?php echo $title; ?></div>
            </strong>
        </div>
    </div>

    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr class="top_hr" />
</header>
