<?php
// 画像を表示
function ViewImage($imageName, $imageUrl, $fileTime) {
//    $imageHtml = new CustomTagCreate();
//    $imageHtml->setImage('');
    echo "<a href='$imageUrl/FILE/$imageName' target='new'><img src='$imageUrl/FILE/$imageName' title='$imageName' width=400px height=400px /></a>";
    echo "<label><input type='checkbox' name='$imageName' value='$imageName' /><span>削除する</span></label> <br/>";
    echo 'アップロード日時: ' . date('Y/m/d H:i:s', $fileTime) . '<br/><br/>';
}

// 画像名を表示
function ViewList($imageName, $imageUrl) {
    echo "<div><a href='$imageUrl/FILE/$imageName' target='new'>{$imageName}</a>";
    echo "<label><input type='checkbox' name='$imageName' value='$imageName' /><span>削除する</span></label></div>";
}
