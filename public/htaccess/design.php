<!-- デザイン用ファイル (PHPで処理を記述)-->
デザイン
<?php
$url = $url. '/public/'. basename(__DIR__);
$imgTest='lgi01a201310220500.jpg';
// var_dump(PublicSetting\GetRequest('mode'));
?>
<img src="<?php echo $url; ?>/<?php echo $imgTest; ?>" width="40" height="40"></a>
