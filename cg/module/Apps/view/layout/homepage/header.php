<head>
    <base href="https://bokkun.xyz/cg/public/">
</head>
<header class='header'>
    <div align="left">
    <?php $img="0113.png"; ?>
        <div>
        <a href="<?php echo $url; ?>"><img src="<?php echo $public; ?>client/image/<?php echo $img; ?>" width="40" height="40"></a>
        <strong>
            <em>Bokkun's homepage</em> <br/>
            <div class="top" align="center"><?php echo $title; ?></div>
        </strong>
        </div>
    </div>
    <?php require_once("../common/jQuery/include.php");  ?>

    <div class='time' align="right"><?php echo date('20y-m-d'); ?></div>
    <div class='time' align="right"><?php echo date('h:i:s'); ?></div>
    <hr class="top_hr" />
</header>
