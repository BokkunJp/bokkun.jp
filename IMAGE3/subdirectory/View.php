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
function _ViewImage($imageName, $imageUrl, $fileTime) {
//    $imageHtml = new CustomTagCreate();
//    $imageHtml->setImage('');
    $imageDirName = basename(getcwd());

    echo "<li>";
    echo "<a href='$imageUrl/{$imageDirName}/$imageName' target='new'><img src='$imageUrl/{$imageDirName}/$imageName' title='$imageName' width=400px height=400px /></a>";
    echo "<p class='image-info'>画像名: {$imageName}<br/>";
    echo "アップロード日時: " . date('Y/m/d H:i:s', $fileTime) . "</p>";
    echo "</li>";
}

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
    $imageDirName = basename(getcwd());

    echo "<li>";
    // echo "<a href='$imageUrl/{$imageDirName}/$imageName' target='new'><img src='$imageUrl/{$imageDirName}/$imageName' title='$imageName' width=400px height=400px /></a>";
    echo "<img src='$imageUrl/{$imageDirName}/$imageName' title='$imageName' class='image' />";
    echo "<p class='image-info'>画像名: {$imageName}<br/>";
    echo "アップロード日時: " . date('Y/m/d H:i:s', $fileTime) . "</p>";
    echo "<div id='back'></div>";
    echo "<div class='imagePopup'><img src='$imageUrl/{$imageDirName}/$imageName' title='$imageName' class='image' /></div>";
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
    $imageDirName = basename(getcwd());
    echo "<div><a href='$imageUrl/{$imageDirName}/$imageName' target='new'>{$imageName}</a>";
    echo "<label><input type='checkbox' name='$imageName' value='$imageName' /><span>削除する</span></label></div>";
}
