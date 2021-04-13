<header class='header'>
    <div align="left">
    <?php $img="crown-vector.jpg"; ?>
        <div>
        <a href="<?php echo $url; ?>"><img src="<?php echo $public; ?>client/image/<?php echo $img; ?>" width="40" height="40"></a>
        <strong>
            <em>βοκκμη's homepage<?= $siteConfig['header']->GetVersion() ?></em> <br/>
            <div class="top" align="center"><?php echo $homepageTitle; ?></div>
        </strong>
        </div>
    </div>

    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr class="top_hr" />
</header>
