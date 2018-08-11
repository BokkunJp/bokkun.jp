<header class='header'>
    <div align="left">
    <?php $img="0113.png"; ?>
        <div>
        <a href="<?php $url ?>"><img src="<?php $url ?>public/client/image/<?php echo $img; ?>" width="40" height="40"></a>
        <strong>
            <em>Bokkun's homepage</em> <br/>
            <div class="top" align="center"><?php if (isset($title)){ echo $title; }else{ echo 'ぼっくんのホームページ';} ?></div>
        </strong>
        </div>
    </div> 

    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr class="top_hr" />
</header>
