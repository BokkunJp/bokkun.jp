<header class='header'>
    <div align="left">
        <?php $img = "0113.png"; ?>
        <div>
            <a href="<?= $base->GetURL('') ?>"><img class='top-image' src="<?= $base->GetURL($img, 'image') ?>"></a>
            <strong>
                <em>Bokkun's homepage</em> <br />
                <div class="top" align="center"><?php if (isset($title)) {
                                                    echo $title;
                                                } else {
                                                    echo 'ぼっくんのホームページ';
                                                } ?></div>
            </strong>
        </div>
    </div>
    <div class='site-lock' align="left"><a href="#" onclick="window.open('https://www.sitelock.com/verify.php?site=bokkun.jp','SiteLock','width=600,height=600,left=160,top=170');"><img class="img-responsive" alt="SiteLock" title="SiteLock" src="//shield.sitelock.com/shield/bokkun.jp" /></a></div>
    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr class="top_hr" />
</header>