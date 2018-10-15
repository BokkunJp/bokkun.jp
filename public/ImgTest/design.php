<?php
// 多角形の点の配列を準備します
$values = array(
            40,  50,  // Point 1 (x, y)
            20,  240, // Point 2 (x, y)
            60,  60,  // Point 3 (x, y)
            240, 20,  // Point 4 (x, y)
            50,  40,  // Point 5 (x, y)
            10,  10   // Point 6 (x, y)
          );
// 画像タグを挿入します
$imageTag = new CustomTagCreate();
$imageTag->SetImage($image. '/'. basename(__DIR__) . '/sample.png', 500, 500);
echo $imageTag->TagExec();
// // 画像を生成します
// $imageFile = file_get_contents($image. '/'. basename(__DIR__) . '/sample.png');
// $image = imagecreatetruecolor(250, 250);
//
// // 色を割り当てます
// $bg   = imagecolorallocate($image, 255, 0, 0);
// $blue = imagecolorallocate($image, 0, 0, 255);
//
// // 背景を塗りつぶします
// imagefilledrectangle($image, 0, 0, 249, 249, $bg);
//
// // 多角形を描画します
// imagefilledpolygon($image, $values, 6, $blue);
//
// // 画像を出力します
// header('Content-type: image/png');
imagepng($image);
imagedestroy($image);
