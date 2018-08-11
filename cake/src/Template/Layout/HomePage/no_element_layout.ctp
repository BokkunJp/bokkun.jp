<!DOCTYPE html>
<html>
    <head>
        <?=$this->Html->charset(); ?>
        <title><?=$this->fetch('title'); ?></title>
        <!-- <link rel="shortcut icon" href="client/image/5959715.png"> -->
        <?php
        echo $this->Html->css('HomePage/PC');
        echo $this->Html->script('HomePage/API/jquery-3.1.1.min.js');
        echo $this->Html->script('HomePage/time');
        echo $this->Html->script('HomePage/realtime');
        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        echo $this->fetch('image');
        ?>
    </head>
    <body>
        <div class="container">
            <header class='header'>
                <div align="left">
                <?php $img="0113.png"; ?>
                    <div>
                    <!-- <a href="<?php echo $url; ?>"><img src="<?php echo $public; ?>client/image/<?php echo $img; ?>" width="40" height="40"></a> -->
                    <?php
                        echo $this->Html->image("HomePage/0113.png", [
                            "class" =>"top-image",
                            "alt" => "Top",
                            'url' => 'https://bokkun.xyz'
                        ]);
                    ?>
                    <strong>
                        <em>Bokkun's homepage</em> <br/>
                        <div class="top" align="center"><?=$title ?></div>
                    </strong>
                    </div>
                </div>

                <div class='date' align="right"></div>
                <div class='time' align="right"></div>
                <hr class="top_hr" />
            </header>
        <div class='contents'>
            <?=$this->fetch('content') ?>
        </div>
            <footer class='footer'>
            <div align='center'>Bokkun's Page</div>
                    <div class="footer_char" align='right'>本ホームページは、<a href="https://www.value-domain.com">バリュードメイン様</a>のレンタルサーバを用いて作成しています。</div>
            <div class="twitter" align='right'><a href="https://twitter.com/intent/tweet?screen_name=bokkun_engineer" class="twitter-mention-button" data-show-count="false">Tweet to @bokkun_engineer</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script></div>
            <small class="copy_right" align='left'>Copyright © 2016-2018 Bokkun All rights reserved.</small>
        </footer>
        </div>
    </body>
</html>
