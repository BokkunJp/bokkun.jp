<header class='header'>
    <div align="left">
    <?php $img="0113.png"; ?>
        <div>
        <?php
            echo $this->Html->image("HomePage/0113.png", [
                "class" =>"top-image",
                "alt" => "Top",
                'url' => 'https://bokkun.jp',
            ]);
        ?>
        <strong>
            <em>Bokkun's homepage v3.0</em> <br/>
            <div class="top" align="center"><?php echo $title; ?></div>
        </strong>
        </div>
    </div>

    <div class='date' align="right"></div>
    <div class='time' align="right"></div>
    <hr class="top_hr" />
</header>
