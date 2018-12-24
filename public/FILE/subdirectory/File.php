<?php

// require_once dirname(dirname(__DIR__)). '/common/Layout/init.php';
require_once ('Page.php');
require_once ('View.php');
$file = PublicSetting\Setting::GetFiles();

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

function ImportImage($file) {
    $imgType = FileExif($file['file']['tmp_name']);
    $imageDir = IMAGE_DIR;

    if (is_numeric($imgType)) {
        if (move_uploaded_file($file['file']['tmp_name'], $imageDir . '/FILE/' . $file['file']['name'])) {
            echo 'ファイルをアップロードしました。<br/>';
        } else {
            echo 'ファイルのアップロードに失敗しました。<br/>';
        }
    } else {
        echo '画像ファイル以外はアップロードできません。<br/>';
    }
}

function LoadAllImageFile() {
    $png = IncludeFiles(IMAGE_DIR . '/FILE/', 'png', true);
    $jpg = IncludeFiles(IMAGE_DIR . '/FILE/', 'jpg', true);
    $jpeg = IncludeFiles(IMAGE_DIR . '/FILE/', 'jpeg', true);
    $gif = IncludeFiles(IMAGE_DIR . '/FILE/', 'gif', true);

    return array_merge($png, $jpg, $jpeg, $gif);
}

/*
  配列を日時でソートする
  引数：
  data 多次元連想配列 (参照渡し)
  Order ASC or DESC
  ※配列にtimeの要素があることが前提

  戻り値：なし
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

function ReadImage($read_flg = 0) {
    if ($read_flg === 0) {
        echo '現在、画像の公開を停止しています。';
        return null;
    } else {
        // アップロードされている画像データを読み込む
        $fileList = LoadAllImageFile();

        // ソート用にデータを調整
        $sortAray = array();
        foreach ($fileList as $index => $_file) {
            $sortAray[$index]['data'] = $_file;
            $sortAray[$index]['time'] = filemtime(IMAGE_DIR . '/FILE/' . $_file);
        }

        // 画像投稿日時の昇順にソート
        TimeSort($sortAray);

        // ソートした順に画像を表示
        $imageUrl = IMAGE_URL;

        ShowImage($sortAray, $imageUrl);
    }
}

function ShowImage($data, $imageUrl) {
    if (GetPage() === false) {
        $start = 1;
    } else {
        $start = (GetPage() - 1) * PAGING + 1;
    }
    $end = $start + PAGING;
    if ($end > count($data)) {
        $end = count($data);
    }

    if ($start > $end) {
        ErrorSet('画像がありません。');
//        ViewPager($data, $imageUrl);
        return false;
    }

    for ($i = $start; $i < $end; $i++) {
        $_file = $data[$i]['data'];
        $_time = $data[$i]['time'];
        ViewImage($_file, $imageUrl, $_time);
    }
    ViewPager($data, $imageUrl);
}

function ErrorSet($errMsg = ERRMessage) {
    $prevLink = new CustomTagCreate();
    $prevLink->TagSet('div', $errMsg, 'error', true);
    $prevLink->TagExec(true);
    $prevLink->SetHref("./FILE/", PREVIOUS, 'page', true, '_self');
}

function DeleteImage() {
    $post = PublicSetting\GetPost();
    $fileList = LoadAllImageFile();
    $count = 0;
    foreach ($post as $post_key => $post_value) {
        if (intval($post_key)) {
            $count++;
            if (in_array($post_value, $fileList)) {
                if (unlink(IMAGE_DIR . '/FILE/' . $post_value) === true) {
                    echo $count . '件目の画像を削除しました。<br/>';
                } else {
                    echo '画像を削除できませんでした。';
                }
            }
        }
    }
    echo '<br/>';
    echo '全' . $count . '件の画像を削除しました。<br/>';
}
