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
 * ImportImage
 * 画像をアップロードする
 *
 * @param  mixed $file
 *
 * @return void
 */
function ImportImage() {
    $upFiles = PrivateSetting\Setting::GetFiles();
    $imageDir = PUBLIC_IMAGE_DIR;

    // データ成型
    $moldFiles = MoldFile($upFiles, 'all-files');

    // アップロード結果
    $result = [];
    // 成功パターン
    $result['success'] = [];
    $result['success']['count'] = 0;
    // ファイルアップロード失敗パターン
    $result['fail'] = [];
    $result['fail']['count'] = 0;
    // ファイルの種類が違うパターン
    $result['-1'] = [];
    $result['-1']['count'] = 0;
    // その他、ファイルのアップロードに失敗したパターン
    // ファイルサイズ系
    // $result['size'] = [];
    // ファイルがない
    // $result['no-file'] = [];
    // tmpディレクトリがない
    // $result['tmp'] = [];

    // ファイル数が規定の条件を超えた場合はアップロード中断
    if (count($moldFiles) > FILE_COUNT_MAX) {
        $result = FILE_COUNT_OVER;
        return $result;
    }

    foreach ($moldFiles as $_files) {
        if (!empty($_files) && $_files['error'] === UPLOAD_ERR_OK) {
            $imgType = FileExif($_files['tmp_name']);
        } else {
            switch ($_files['error']) {
                case UPLOAD_ERR_NO_FILE:
                    return null;
                    break;
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:

                case UPLOAD_ERR_PARTIAL:
                case UPLOAD_ERR_NO_TMP_DIR:
                case UPLOAD_ERR_CANT_WRITE:
                case UPLOAD_ERR_EXTENSION:
                    $result['fail']['count']++;
                break;
            }
            continue;
        }

        if (is_numeric($imgType)) {
            if (move_uploaded_file($_files['tmp_name'], $imageDir . '/FILE/' . $_files['name'])) {
                // $result['success'][$_files['name']] = true;
                $result['success']['count']++;
            } else {
                // $result['fail'][$_files['name']] = false;
                $result['fail']['count']++;
            }
        } else {
            // $result['-1'][$_files['name']] = -1;
            $result['-1']['count']++;
        }
    }


    return $result;

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
        $imgSrc[mb_strtolower($_index)] = IncludeFiles(PUBLIC_IMAGE_DIR . '/FILE/', mb_strtolower($_index), true);
        $imgSrc[mb_strtoupper($_index)] = IncludeFiles(PUBLIC_IMAGE_DIR . '/FILE/', mb_strtoupper($_index), true);
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
            $sortAray[$index]['time'] = filemtime(PUBLIC_IMAGE_DIR . '/FILE/' . $_file);
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
        $start = ($page - 1) * GetPaging() + 1;
    }
    $end = $start + GetPaging();
    if ($end > count($data)) {
        $end = count($data);
    }

    if ($start >= $end) {
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
    $prevLink->TagSet('div', $errMsg, 'warning', true);
    $prevLink->TagExec(true);
    $prevLink->SetHref("./FILE/", PREVIOUS, 'page', true, '_self');

}

/**
 * DeleteImage
 * 画像を一括削除する
 *
 * @return void
 */
function DeleteImage() {
    $post = PrivateSetting\Setting::getPosts();
    $fileList = LoadAllImageFile();
    $count = 0;

    // _oldディレクトリがない場合はディレクトリを生成
    if (!is_dir(PUBLIC_IMAGE_DIR . '/FILE/_old/')) {
        mkdir(PUBLIC_IMAGE_DIR . '/FILE/_old/');
    }
    var_dump(is_dir(PUBLIC_IMAGE_DIR . '/FILE/_old/'));die;
    foreach ($post as $post_key => $post_value) {
        if ($post_key !== 'token' && $post_key !== 'delete') {
            $count++;
            if (in_array($post_value, $fileList)) {
                if (!rename(PUBLIC_IMAGE_DIR . '/FILE/' . $post_value, PUBLIC_IMAGE_DIR . '/FILE/_old/' . $post_value) === true) {
                    return false;
                }
            }
        }
    }
    return true;
}
