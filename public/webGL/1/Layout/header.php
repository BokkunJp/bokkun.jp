<header class='header'>
    <div align="left">
        <?php $img = "0113.png"; ?>
        <div>
            <a href="<?php echo $base->GetURL(); ?>"><img src="<?php echo $public; ?>client/image/<?php echo $img; ?>" width="40" height="40"></a>
            <strong>
                <em>Bokkun's homepage</em> <br />
                <div class="top" align="center"><?php echo $title; ?></div>
            </strong>
        </div>
    </div>
    <?php require_once(PUBLIC_COMMON_DIR . "/Load/include.php"); ?>
    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr class="top_hr" />
    <p class='bread'><?php ViewArray($breadCrumbList, $arrow); ?></p>
</header>