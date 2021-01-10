<?php
/**
 * ViewImage
 * 画像を表示する
 *
 * @param  mixed $imageName
 * @param  mixed $imageUrl
 * @param  mixed $fileTime
 *
 * @return void
 */
function ViewImage($imageName, $imageUrl, $fileTime) {
//    $imageHtml = new CustomTagCreate();
//    $imageHtml->setImage('');
    echo "<li>";
    echo "<a href='$imageUrl/IMAGE/$imageName' target='new'><img src='$imageUrl/IMAGE/$imageName' title='$imageName' width=400px height=400px /></a>";
    echo "<p class='image-info'>画像名: {$imageName}<br/>";
    echo "アップロード日時: " . date('Y/m/d H:i:s', $fileTime) . "</p>";
    echo "</li>";
}

/**
     * ViewList
     * 画像をリスト表示する
     *
     * @param  mixed $imageName
     * @param  mixed $imageUrl
     *
     * @return void
     */
function ViewList($imageName, $imageUrl) {
    echo "<div><a href='$imageUrl/IMAGE/$imageName' target='new'>{$imageName}</a>";
    echo "<label><input type='checkbox' name='$imageName' value='$imageName' /><span>削除する</span></label></div>";
}
