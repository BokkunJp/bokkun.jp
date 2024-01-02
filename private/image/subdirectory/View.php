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
function viewImage($imageName, $imageUrl, $fileTime, $checked = false): void
{
//    $imageHtml = new CustomTagCreate();
//    $imageHtml->setImage('');

    // 現在選択している画像タイプを取得
    $imagePageName = getImagePageName();

    // iniの内容を取得してセッションに保存
    $session = new Private\Important\Session();
    if (empty($session->read('imageMaxSize-ini'))) {
        $session->write("imageMaxSize-ini", (int)getIni('private', 'ImageMaxSize'));
    }

    $imagePath = new \Path(PUBLIC_DIR_LIST['image']);
    $imagePath->addArray([$imagePageName, $imageName]);
    $imageData = calcImageSize($imagePath->get(), $session->read('imageMaxSize-ini'));

    // 画像データが存在する場合は出力
    if ($imageData) {
        echo "<div class='image-info1'><span class='image-name'>画像名:{$imageName}</span> ";
        echo "<span class='image-size'>({$imageData['size']}{$imageData['sizeUnit']}B)</span></div>";
        echo "<div class='image-info2'><a href='{$imageUrl}/{$imagePageName}/{$imageName}' target='new'><img src='$imageUrl/$imagePageName/$imageName' title='$imageName' width=400px height=400px /></a>";
        echo "<label><input type='checkbox' class='image-check' name='img_{$imageName}' value='$imageName' $checked /><span>削除・コピーする</span></label> <br/>";
        echo 'アップロード日時: ' . date('Y/m/d H:i:s', $fileTime) . '</div><br/>';
    }
}

/**
 * viewList
 * 画像をリスト表示する
 *
 * @param  mixed $imageName
 * @param  mixed $imageUrl
 *
 * @return void
 */
function viewList($imageName, $imageUrl, $checked = false): void
{
    // 現在選択している画像タイプを取得
    $imagePageName = getImagePageName();

    echo "<div><a href='$imageUrl/$imagePageName/$imageName' target='new'>{$imageName}</a>";
    echo "<label><input type='checkbox' class='image-check' name='img_{$imageName}' value='$imageName' $checked /><span>削除・コピーする</span></label></div>";
}
