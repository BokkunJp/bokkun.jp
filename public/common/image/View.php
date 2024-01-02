<?php
/**
 * viewImage
 * 画像を表示する
 *
 * @param  string $imageName
 * @param  string $imageUrl
 * @param  mixed $fileTime
 *
 * @return void
 */
function viewImage($imageName, $imageUrl, $fileTime): void
{
//    $imageHtml = new CustomTagCreate();
//    $imageHtml->setImage('');
    $imageDirName = basename(getcwd());

    echo "<li>";
    echo "<a href='$imageUrl/{$imageDirName}/$imageName' target='new'><img src='$imageUrl/{$imageDirName}/$imageName' title='$imageName' width=400px height=400px /></a>";
    echo "<p class='image-info'>";
    echo "画像名: {$imageName}<br/>";
    echo "アップロード日時: " . date('Y/m/d H:i:s', $fileTime);
    echo "</p>";
    echo "</li>";
}

/**
     * viewList
     * 画像をリスト表示する
     *
     * @param  string $imageName
     * @param  string $imageUrl
     *
     * @return void
     */
function viewList($imageName, $imageUrl): void
{
    $imageDirName = basename(getcwd());
    echo "<div><a href='$imageUrl/{$imageDirName}/$imageName' target='new'>{$imageName}</a>";
}
