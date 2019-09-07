<canvas class='3d'>本ブラウザでは対応していません。</canvas>

<?php
//アクセス記録を残すPath（テンポラリ/IP）を決定
$file = PUBLIC_CLIENT_DIR . '/tmp/'. basename(__DIR__). '.log';

//アクセスログを記録
//ファイル名はIPで中身はunixtime
//厳密に書き込むならロック制御が必要(ロックされてるとE_NOTICEかE_WARNINGがでるハズ)
file_put_contents($file, time() . PHP_EOL, FILE_APPEND | LOCK_EX);

//読み取り間隔を設定
//lineSizeはunixtime10桁+改行固定(11桁になるのは2286年)
$lineSize = 11;
//読み取り行数
$maxRow = 5;
//連続アクセスしたとするしきい値（秒）
$limitTime = 10;
//読み取るバイト数
$readByte = $lineSize * $maxRow;
//バイト数分後ろから読む
$readContent = file_get_contents($file, false, null, filesize($file) - $readByte);
$lines = explode(PHP_EOL, $readContent);
//バイト単位で読むので先頭がかけてる場合を想定して予め先頭行を切り落とす
array_shift($lines);

//初回と連続アクセス規制に満たなければ通過させる
if ($lines[0] + $limitTime < time() || count($lines) < $maxRow) {
    return;
}
echo 'しばらくおまちください (残り：'. ($lines[0] - (time() - $limitTime)) . '秒)';
return;
?>
