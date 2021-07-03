<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
class Scratch {
    use PublicTrait;
}
$sElm = new Scratch();
$sElm->Output('test');