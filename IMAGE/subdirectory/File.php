<?php
use BasicTag\CustomTagCreate;

// require_once dirname(dirname(__DIR__)). '/common/Layout/init.php';
require_once ('Page.php');
require_once ('View.php');

/**
 * FileExif
 * 画像タイプのExifを返す
 *
 * @param  mixed $img
 *
 * @return void
 */
function FileExif($img) {
    // echo exif_imagetype($img). '<br />';
    // switch (exif_imagetype($img)) {
    //     case IMAGETYPE_JPEG:
    //     break;
    //     case IMAGETYPE_GIF:
    //     break;
    //     case IMAGETYPE_PNG:
    //     break;
    //     default:
    //     break;
    // }

    return exif_imagetype($img);
}

/**
 * ImportImage
 * ファイルデータを成型する
 *
 * @param  mixed $file
 * @param  string $fileName
 *
 * @return array
 */
function MoldFile($file, String $fileName)
{
    $moldFiles = [];

    // FILEが配列でない場合は中断
    if (!is_array($file)) {
        return false;
    }

    foreach ($file[$fileName] as $_key => $_files) {
        foreach ($_files as $__key => $__val) {
            $moldFiles[$__key][$_key] = $__val;
        }
    }

    return $moldFiles;
}

/**
 * LoadAllImageFile
 * 画像ファイル名を配列で一括取得する
 *
 * @return array
 */
function LoadAllImageFile() {
    $imgArray = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

    $imgSrc = [];
    foreach ($imgArray as $_index) {
        $imgSrc[mb_strtolower($_index)] = IncludeFiles(PUBLIC_IMAGE_DIR . '/IMAGE/', mb_strtolower($_index), true);
        $imgSrc[mb_strtoupper($_index)] = IncludeFiles(PUBLIC_IMAGE_DIR . '/IMAGE/', mb_strtoupper($_index), true);
    }

    $ret = [];
    foreach ($imgSrc as $_index => $_img) {
        if (isset($_img)) {
            foreach ($_img as $__val) {
                $ret[] = $__val;
            }
        }
    }

    // var_dump(array_merge($png, $png_2, $jpg, $jpg_2, $jpeg, $gif));

    return $ret;
}

/**
 * TimeSort
 * 配列を日時でソートする
 *
 * @param  mixed $data
 * @param  mixed $order
 *
 * @return void
 */
function TimeSort(&$data, $order = 'ASC') {

    if (is_array($data) == false) {
        echo 'データは配列でなければいけません。';
        return -1;
    }

    $time = [];
    foreach ($data as $_data) {
        // データ内に必要な要素があるかチェック
        if (array_key_exists('time', $_data) == false) {
            echo '必要な要素がありません。';
            return -1;
        }
        $time[] = $_data['time'];  // 時刻データ配列を生成
        // $time[] = strtotime($_data['time']);  // 時刻を調整
    }

    // 順番の指定
    if ($order === 'ASC') {
        $sort = SORT_ASC;
    } else if ($order === 'DESC') {
        $sort = SORT_DESC;
    } else {
        echo '順序指定が不正です。';
        return -1;
    }

    array_multisort($time, $sort, $data);
}

/**
 * ReadImage
 * 画像を読み込み、公開する
 *
 * @param  mixed $read_flg
 *
 * @return void
 */
function ReadImage($read_flg = NOT_VIEW) {
    if ($read_flg === NOT_VIEW) {
        echo '現在、画像の公開を停止しています。';
        return NOT_VIEW;
    } else {
        // アップロードされている画像データを読み込む
        $fileList = LoadAllImageFile();

        // ソート用にデータを調整
        $sortAray = array();
        foreach ($fileList as $index => $_file) {
            $sortAray[$index]['name'] = $_file;
            $sortAray[$index]['time'] = filemtime(PUBLIC_IMAGE_DIR . '/IMAGE/' . $_file);
        }

        // 画像投稿日時の昇順にソート
        TimeSort($sortAray);

        // ソートした順に画像を表示
        $imageUrl = IMAGE_URL;

        ShowImage($sortAray, $imageUrl);
    }
}

/**
 * ShowImage
 * 画像一覧を公開する
 *
 * @param  mixed $data
 * @param  mixed $imageUrl
 *
 * @return void
 */
function ShowImage($data, $imageUrl) {
    $page = GetPage();
    if ($page <= 0 || $page === false) {
        ErrorSet('ページの指定が不正です。');
        return false;
    } else {
        $start = ($page - 1) * GetCountPerPage();
    }
    $end = $start + GetCountPerPage();
    if ($end > count($data)) {
        $end = count($data);
    }

    if ($start >= $end) {
        ErrorSet('画像がありません。');
//        ViewPager($data);
        return false;
    }

    Output('<p><a href="#update_page">一番下へ</a></p>');

    for ($i = $start; $i < $end; $i++) {
        $_file = $data[$i]['name'];
        $_time = $data[$i]['time'];
        ViewImage($_file, $imageUrl, $_time);

    }
    ViewPager($data);
}

/**
 * ErrorSet
 * エラー文を定義する
 *
 * @param  mixed $errMsg
 *
 * @return void
 */
function ErrorSet($errMsg = ERRMessage) {
    $prevLink = new CustomTagCreate();
    $prevLink->SetTag('div', $errMsg, 'warning', true);
    $prevLink->ExecTag(true);
    $prevLink->SetHref("./", PUBLIC_PREVIOUS, 'page', true, '_self');

}
