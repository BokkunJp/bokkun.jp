<?php
/**
 * ViewImage
 * 画像を表示する
 *
 * @param void
 *
 * @return void
 */
function JudgeView($readFlg) {
    $sess = new PrivateSetting\Session();
    $sess->Read('read_flg');
}

/**
 * ViewImage
 * 画像を表示する
 *
 * @param  string $imageName
 * @param  string $imageUrl
 * @param  mixed $fileTime
 *
 * @return void
 */
function ViewImage($imageName, $imageUrl, $fileTime) {
//    $imageHtml = new CustomTagCreate();
//    $imageHtml->setImage('');

// 現在選択している画像タイプを取得
    $imagePageName = GetImagePageName();

    echo "<a href='$imageUrl/$imagePageName/$imageName' target='new'><img src='$imageUrl/$imagePageName/$imageName' title='$imageName' width=400px height=400px /></a>";
    echo "<label><input type='checkbox' name='img_{$imageName}' value='$imageName' /><span>削除・コピーする</span></label> <br/>";
    echo 'アップロード日時: ' . date('Y/m/d H:i:s', $fileTime) . '<br/><br/>';
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
    // 現在選択している画像タイプを取得
    $imagePageName = GetImagePageName();

    echo "<div><a href='$imageUrl/$imagePageName/$imageName' target='new'>{$imageName}</a>";
    echo "<label><input type='checkbox' name='$imageName' value='$imageName' /><span>削除・コピーする</span></label></div>";
}
