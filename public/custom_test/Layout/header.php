<header class='header'>
    <div class='header_upper' align="left">
    <?php $img="crown-vector.jpg"; ?>
        <div class='header_title'>
        <a href="<?php echo $url; ?>"><img src="<?php echo $public; ?>client/image/<?php echo $img; ?>" width="40" height="40"></a>
        <strong>
            <em>〇〇's homepage</em> <br/>
            <div class="top" align="center"><?php echo $title; ?></div>
        </strong>
        </div>
        <ul class='menu'>
          <li class='menu_test'>Test</li>
          <li class='menu_test'>Test1</li>
          <li class='menu_test'>Test2</li>
          <li class='menu_test'>Test3</li>
        </ul>
        <div class='date' align="right"></div>
        <div class='time' align="right"></div>
    </div>
    <?php require_once(PUBLIC_COMMON_DIR. "/jQuery/include.php");  ?>

    <hr class="top_hr" />
    <!-- <p class='bread'><?php ViewArray($breadCrumbList, $arrow); ?></p> -->
</header>
