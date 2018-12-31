<!DOCTYPE html>
<html>
    <head>
        <?=$this->Html->charset(); ?>
        <?= $this->html->meta('icon', 'img/HomePage/favicon.ico');?>
        <title><?=$this->fetch('title'); ?></title>
        <!-- <link rel="shortcut icon" href="client/image/5959715.png"> -->
        <?php
        if ($this->request->ismobile()) {
          echo $this->Html->css('HomePage/SMP');
        } else {
          echo $this->Html->css('HomePage/PC');
        }
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
        <!-- ビューでエレメントを指定　 -->
        <?=$this->element('HomePage/header'); ?>
        <div class='contents'>
            <?=$this->fetch('content') ?>
        </div>
        <!-- ビューでエレメントを指定　 -->
        <?=$this->element('HomePage/footer'); ?>
        </div>
    </body>
</html>
