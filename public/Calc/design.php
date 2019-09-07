<?php
$val = [
    rand() % 10, rand() % 10,
    rand() % 10, rand() % 10,
    rand() % 10, rand() % 10,
    rand() % 10, rand() % 10 + 1
];
?>
<form method='POST' action='.'>
    <div class='calc' id='q1'>
        <span class='value' id='val1' value=<?= $val[0] ?>><?= $val[0] ?></span>
        + <span class='value' id='val2' value=<?= $val[1] ?>><?= $val[1] ?></span>
        =
        <input name='answer1' />
        <span id='judge1'></span>
    </div>
    <div class='calc' id='q2'>
        <span class='value' id='val3' value=<?= $val[2] ?>><?= $val[2] ?></span>
        ー <span class='value' id='val4' value=<?= $val[3] ?>><?= $val[3] ?></span>
        =
        <input name='answer2' />
        <span id='judge2'></span>
    </div>
    <div class='calc' id='q3'>
        <span class='value' id='val5' value=<?= $val[4] ?>><?= +$val[4] ?></span>
        × <span class='value' id='val6' value=<?= $val[5] ?>><?= $val[5] ?></span>
        =
        <input name='answer3' />
        <span id='judge3'></span>
    </div>
    <div class='calc' id='q4'>
        <span class='value' id='val7' value=<?= $val[6] ?>><?= $val[6] ?></span>
        ÷ <span class='value' id='val8' value=<?= $val[7] ?>><?= $val[7] ?></span>
        =
        <input name='answer4' />
        <span id='judge4'></span>
    </div>
    <div class='judge'>
        正否判定:<span id='judge'></span>
    </div>
    <div class='score'>
        スコア:<span id='score'></span>
        <button type='button' id='reset'>スコアリセット</button>
    </div>
</form>