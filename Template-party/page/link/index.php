<?php
// 必要なファイルの読み込み
$data = scandir(__DIR__);
foreach ($data as $_i => $_d) {
    if (preg_match('/\.$/', $_d) || preg_match('/^\.\.$/', $_d)|| preg_match('/[php]$/', $_d)) {
        unset($data[$_i]);
    }
}

if (empty($data)) {
    $firstPath = dirname(__DIR__, 3);
    $dirPath =  dirname(__DIR__, 2);
    $pagePath = dirname(__DIR__);
} else {
    $firstPath = dirname(__DIR__, 2);
    $dirPath =  dirname(__DIR__);
    $pagePath = __DIR__;
}

require_once $firstPath . '/public/common/Layout/require.php';

// 対象ディレクトリの決定
$dirName = basename($dirPath);
$pageName = basename($pagePath);

// phpのディレクトリ指定
$phpPath = \Path::AddPathStatic($dirName, $pageName, false);

// cssのディレクトリ指定
if (is_dir(\Path::AddPathStatic(\Path::AddPathStatic(PUBLIC_CSS_DIR, $dirName), $pageName))) {
    $cssPath = \Path::AddPathStatic($dirName, $pageName, false);
} else {
    $cssPath = $dirName;
}

// jsのディレクトリ指定
if (is_dir(\Path::AddPathStatic(\Path::AddPathStatic(PUBLIC_JS_DIR, $dirName), $pageName))) {
    $jsPath = \Path::AddPathStatic($dirName, $pageName, false);
} else {
    $jsPath = $dirName;
}

// imageのディレクトリ指定
if (is_dir(\Path::AddPathStatic(\Path::AddPathStatic(PUBLIC_IMAGE_DIR, $dirName), $pageName))) {
    $imagePath = \Path::AddPathStatic($dirName, $pageName, false);
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
<link rel="stylesheet" href="/public/client/css/<?=$cssPath?>/style-opening.css">
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

<h1 id="logo"><a href="/<?=$phpPath?>"><img src="/public/client/image/<?=$imagePath?>/logo.png" alt="SAMPLE SITE"></a></h1>

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

<h2>リンク</h2>

<table class="ta1">
<caption>見出しが必要であればここを使います</caption>
<tr>
<th><a href="#"><img src="/public/client/image/<?=$imagePath?>/mainimg.jpg" alt=""><br>
サイト名</a></th>
<td>ここに説明など入れて下さい。サンプルテキスト。</td>
</tr>
<tr>
<th><a href="#">サイト名</a></th>
<td>ここに説明など入れて下さい。サンプルテキスト。</td>
</tr>
<tr>
<th><a href="#">サイト名</a></th>
<td>ここに説明など入れて下さい。サンプルテキスト。</td>
</tr>
<tr>
<th><a href="#">サイト名</a></th>
<td>ここに説明など入れて下さい。サンプルテキスト。</td>
</tr>
<tr>
<th><a href="#">サイト名</a></th>
<td>ここに説明など入れて下さい。サンプルテキスト。</td>
</tr>
<tr>
<th><a href="#">サイト名</a></th>
<td>ここに説明など入れて下さい。サンプルテキスト。</td>
</tr>
<tr>
<th><a href="#">サイト名</a></th>
<td>ここに説明など入れて下さい。サンプルテキスト。</td>
</tr>
<tr>
<th><a href="#">サイト名</a></th>
<td>ここに説明など入れて下さい。サンプルテキスト。</td>
</tr>
</table>

</section>

<footer>
<small>Copyright&copy; <a href="/<?=$phpPath?>">SAMPLE SITE</a> All Rights Reserved.</small>
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

</body>
</html>
