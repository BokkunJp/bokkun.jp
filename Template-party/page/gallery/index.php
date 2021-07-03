<?php
// 必要なファイルの読み込み
require_once dirname(__DIR__, 3) . '/public/common/Layout/require.php';

// 対象ディレクトリの決定
if (basename(dirname(__DIR__, 2)) !== basename(DOCUMENT_ROOT)) {
    $dirName = basename(dirname(__DIR__, 2));
    $pageName = basename(dirname(__DIR__));
} else {
    $dirName = basename(dirname(__DIR__));
    $pageName = basename(__DIR__);
}

// phpのディレクトリ指定
$phpPath = "";
if ($dirName !== basename(DOCUMENT_ROOT)) {
    $phpPath = $dirName;
} else {
    $dirName = $pageName;
}
if ($pageName !== basename(__DIR__)) {
    $phpPath = AddPath($phpPath, $pageName, false, "/");
}

// cssのディレクトリ指定
if (is_dir(AddPath(AddPath(PUBLIC_CSS_DIR, $dirName), $pageName))) {
    $cssPath = AddPath($dirName, $pageName, false);
} else {
    $cssPath = $dirName;
}

// jsのディレクトリ指定
if (is_dir(AddPath(AddPath(PUBLIC_JS_DIR, $dirName), $pageName))) {
    $jsPath = AddPath($dirName, $pageName, false);
} else {
    $jsPath = $dirName;
}

// imageのディレクトリ指定
if (is_dir(AddPath(AddPath(PUBLIC_IMAGE_DIR, $dirName), $pageName))) {
    $imagePath = AddPath($dirName, $pageName, false);
} else {
    $imagePath = $dirName;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>個人サイト向け 無料ホームページテンプレート tp_simple16</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="ここにサイト説明を入れます">
<meta name="keywords" content="キーワード１,キーワード２,キーワード３,キーワード４,キーワード５">
<link rel="stylesheet" href="/public/client/css/<?=$cssPath?>/style.css">
<script src="/public/client/js/<?=$jsPath?>/fixmenu_pagetop.js"></script>
<script src="/public/client/js/<?=$jsPath?>/openclose.js"></script>
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>

<div id="container">

<header>

<h1 id="logo"><a href="indexphp"><img src="/public/client/image/<?=$imagePath?>/logo.png" alt="SAMPLE SITE"></a></h1>

<!--PC用（801px以上端末）メニュー-->
<nav id="menubar">
<ul>
<li><a href="/<?=$phpPath?>/">Home</a></li>
<li><a href="/<?=$phpPath?>/about/">About</a></li>
<li><a href="/<?=$phpPath?>/gallery/">Gallery</a></li>
<li><a href="/<?=$phpPath?>/link/">Link</a></li>
</ul>
</nav>

<ul class="icon">
<li><a href="#"><img src="/public/client/image/<?=$imagePath?>/icon_facebook.png" alt="Facebook"></a></li>
<li><a href="#"><img src="/public/client/image/<?=$imagePath?>/icon_twitter.png" alt="Twitter"></a></li>
<li><a href="#"><img src="/public/client/image/<?=$imagePath?>/icon_instagram.png" alt="Instagram"></a></li>
</ul>

</header>

<div id="contents">

<section class="inner first">

<h2>Gallery</h2>

<figure id="item-image">
<img src="/public/client/image/<?=$imagePath?>/sample1_big.jpg" alt="写真の説明を入れます" id="item_image1">
<img src="/public/client/image/<?=$imagePath?>/sample1_big.jpg" alt="写真の説明を入れます" id="item_image2">
<p id="imgcaption">画像１の説明文をここに入れます</p>
</figure>

<div id="thumbnail-box">
<img src="/public/client/image/<?=$imagePath?>/sample1_big.jpg" alt="画像１の説明文をここに入れます" class="thumbnail">
<img src="/public/client/image/<?=$imagePath?>/sample2.jpg" alt="画像２の説明文をここに入れます" class="thumbnail">
<img src="/public/client/image/<?=$imagePath?>/sample3.jpg" alt="画像３の説明文をここに入れます" class="thumbnail">
<img src="/public/client/image/<?=$imagePath?>/sample4.jpg" alt="画像４の説明文をここに入れます" class="thumbnail">
<img src="/public/client/image/<?=$imagePath?>/sample5.jpg" alt="画像５の説明文をここに入れます" class="thumbnail">
</div>

<h3>イメージチェンジプログラム（imgchg_pack.js）の使い方</h3>
<p>サムネイル画像をクリックすると、大きな写真が入れ替わります。<br>
html側を見れば分かりますが、大きな画像の読みこみ行は２行あります。違う点はidの指定名が異なるだけですが必ず２行入れて下さい。この２枚の画像と、サムネイルの１枚目の画像名は合わせておいて下さい。<br>
サムネイルを増やしたい場合はhtml側のサムネイルの行をコピペで増やし、画像ファイル名やalt指定（今回の場合は大きな写真下に表示される説明文になります）を入れ替えて下さい。</p>
<p><strong class="color1">サムネイルと拡大画像は兼用です。</strong><br>
サムネイルのサイズはcssフォルダのstyle.cssの「.thumbnail」のwidthとheightの値で変更できます。小さな端末での再設定がある場合もあるのでチェックしましょう。</p>
<p><strong class="color1">画像の縦横比率は全て統一しておいた方が切り替えがスムーズです。</strong><br>
わかりやすいよう、中央のサムネイルだけ画像の高さが他と異なる設定にしているので実際にクリックして状態を見てみて下さい。</p>
<p><strong class="color1">写真の枚数を増やす場合</strong>、ウィンドウ幅によっては段落ちしてレイアウトが崩れるので、「.thumbnail」のwidthとheightの数字を小さくしてバランスをとって下さい。</p>
<p><strong class="color1">バランスを取るのが難しいぐらいの大量の写真を配置する場合</strong>、大きな画像の上に配置するのではなく、画像の外側に置いた方がいいでしょう。<br>
style.cssの#item-imageのmargin-bottom: -50px;の１行を削除。<br>
#thumbnail-boxのbottom: 70px;の１行を削除すれば通常の出力場所（説明文の下）に出力されます。</p>
<p><strong class="color1">他のテンプレートにパーツを使う場合</strong><br>
画像ブロックのhtmlをそのままコピペ。<br>
html下部の&lt;!-- イメージチェンジ設定 --&gt;からの数行もコピペ。jsフォルダのimgchg_pack.jsをご利用中のjsフォルダに移動。jsフォルダがなければ新規で作るか、htmlのjsファイルの読み込みパスを変更して下さい。<br>
style.cssの「/*galleryページの画像切り替え」ブロックもコピペ。小さな端末の再設定もあるので忘れず。</p>
<p><strong class="color1">当サイトのテンプレート以外にパーツを使う場合</strong><br>
当テンプレートに梱包されているjavascriptファイルは全て<a href="http://www.crytus.co.jp/">有限会社クリタス様</a>提供のものです。jsファイルは改変せずにご利用下さい。<br>
また、当サイトのテンプレート「以外」に使いたいなど、「プログラムのみ」を使う場合は<a href="http://template-party.com/free_program/openclose_license.html">こちらの規約</a>をお守り下さい。</p>

</section>

<footer>
<small>Copyright&copy; <a href="">SAMPLE SITE</a> All Rights Reserved.</small>
<span class="pr">《<a href="https://template-party.com/" target="_blank">Web Design:Template-Party</a>》</span>
</footer>

</div>
<!--/#contents-->

</div>
<!--/#container-->

<!--オープニングアニメーション-->
<aside id="mainimg">
<img src="/public/client/image/<?=$imagePath?>/1.png" alt="" class="photo photo1">
<img src="/public/client/image/<?=$imagePath?>/1.png" alt="" class="photo photo2">
<img src="/public/client/image/<?=$imagePath?>/1.png" alt="" class="photo photo3">
<img src="/public/client/image/<?=$imagePath?>/1.png" alt="" class="photo photo4">
<img src="/public/client/image/<?=$imagePath?>/1.png" alt="" class="photo photo5">
<img src="/public/client/image/<?=$imagePath?>/1.png" alt="" class="photo photo6">
<img src="/public/client/image/<?=$imagePath?>/1.png" alt="" class="photo photo7">
<img src="/public/client/image/<?=$imagePath?>/1.png" alt="" class="photo photo8">
<img src="/public/client/image/<?=$imagePath?>/1.png" alt="" class="photo photo9">
</aside>

<!--小さな端末用（800px以下端末）メニュー-->
<nav id="menubar-s">
<ul>
<li><a href="/<?=$phpPath?>/">Home</a></li>
<li><a href="/<?=$phpPath?>/about/">About</a></li>
<li><a href="/<?=$phpPath?>/gallery/">Gallery</a></li>
<li><a href="/<?=$phpPath?>/link/">Link</a></li>
</ul>
</nav>

<!--ページの上部に戻る「↑」ボタン-->
<p class="nav-fix-pos-pagetop"><a href="#">↑</a></p>

<!--メニュー開閉ボタン-->
<div id="menubar_hdr" class="close"></div>

<!--メニューの開閉処理条件設定　800px以下-->
<script>
if (OCwindowWidth() <= 800) {
	open_close("menubar_hdr", "menubar-s");
}
</script>

<!-- イメージチェンジ設定 -->
<script src="/public/client/js/<?=$jsPath?>/imgchg_pack.js"></script>
<script>
imgchg_start('item_image1', 'item_image2', 'thumbnail', 'imgcaption', 0);
</script>

</body>
</html>
