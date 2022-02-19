<?php

use BasicTag\CustomTagCreate;

// require_once dirname(dirname(__DIR__)). '/common/Layout/init.php';
require_once('Page.php');
require_once('View.php');

/**
 * FileExif
 * 画像タイプのExifを返す
 *
 * @param  mixed $img
 *
 * @return void
 */
function FileExif($img)
{
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
function LoadAllImageFile()
{
    $imgArray = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'];

    $imageDirName = basename(getcwd());

    $imgSrc = [];
    foreach ($imgArray as $_index) {
        $imgSrc[mb_strtolower($_index)] = IncludeFiles(PUBLIC_IMAGE_DIR . "/{$imageDirName}/", mb_strtolower($_index), true);
        $imgSrc[mb_strtoupper($_index)] = IncludeFiles(PUBLIC_IMAGE_DIR . "/{$imageDirName}/", mb_strtoupper($_index), true);
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
function TimeSort(&$data, $order = 'ASC')
{
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
    } elseif ($order === 'DESC') {
        $sort = SORT_DESC;
    } else {
        echo '順序指定が不正です。';
        return -1;
    }

    array_multisort($time, $sort, $data);
}

/**
 * ValidParameter
 * ページ関係の内容の検証
 *
 * @param array $data
 * @param boolean $ajaxFlg
 * @return array
 */
function ValidParameter($data=[], $ajaxFlg=false)
{
    // 現在のページ番号の取得
    $page = GetPage();

    // 結果配列
    $result = null;
    if ($page <= 0 || $page === false) {
        if ($ajaxFlg === false) {
            Output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
            Output("<div class='image-list'>", indentFlg:false);
            ErrorSet('ページの指定が不正です。');
            Output("</div><div class='image-pager'></div>", indentFlg:false);
        }
        return ['result' => false, 'view-image-type' => basename(getcwd())];
    } else {
        $start = ($page - 1) * GetCountPerPage();
    }
    $end = $start + GetCountPerPage();
    if ($end > count($data)) {
        $end = count($data);
    }

    if ($start >= $end) {
        if ($ajaxFlg === false) {
            Output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
            Output("<div class='image-list'>", indentFlg:false);
            ErrorSet('画像がありません。');
            Output("</div><div class='image-pager'></div>", indentFlg:false);
        }
        $result = ['result' => false, 'view-image-type' => basename(getcwd())];
    }

    if (!isset($result)) {
        $result = ['start' => $start, 'end' => $end, 'max' => count(LoadAllImageFile())];
    }

    return $result;
}

/**
 * ChoiseImage
 * 全画像データのうち、表示に必要なデータのみを抽出する
 *
 * @param array $params
 * @param array $data
 * @return void
 */
function ChoiseImage($params, $data)
{
    // 結果用配列
    $cloneImg = [];
    for ($i = $params['start'];$i < $params['end']; $i++) {
        $cloneImg[$i] = $data[$i];
    }

    return $cloneImg;
}

/**
 * ReadImage
 * 画像を読み込み、公開する
 *
 * @param  mixed $read_flg
 *
 * @return void
 */
function ReadImage()
{

    // 現在のページを取得
    $imagePageName = basename(getcwd());

    // アップロードされている画像データを読み込む
    $fileList = LoadAllImageFile();

    // ソート用にデータを調整
    $sortAray = array();
    foreach ($fileList as $index => $_file) {
        $sortAray[$index]['name'] = $_file;
        $sortAray[$index]['time'] = filemtime(AddPath(AddPath(PUBLIC_IMAGE_DIR, $imagePageName), $_file, false));
    }

    // 画像投稿日時の昇順にソート
    TimeSort($sortAray);

    // ページ関連で必要なデータの検証
    $params = ValidParameter($sortAray);
    if (isset($params['result']) && $params['result'] === false) {
        return ['result' => false];
    }

    // 画像データを整理
    $sortAray = ChoiseImage($params, $sortAray);

    ShowImage($params, $sortAray, IMAGE_URL);
}

/**
 * ShowImage
 * 画像一覧を公開する
 *
 * @param array $params
 * @param array $data
 * @param string $imageUrl
 * @param boolean $ajaxFlg
 * @return void
 */
function ShowImage($params, $data, $imageUrl)
{
    // セッション開始
    if (!isset($session)) {
        $session = new PublicSetting\Session();
    }

    // jQueryで書き換えれるように要素を追加
    Output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
    Output("<div class='image-box'  ontouchstart=''>", indentFlg:false);
    Output("<ul>", indentFlg:false);
    foreach ($data as $i => $_data) {
        $_file = $_data['name'];
        $_time = $_data['time'];
        // コピーチェック用のセッションを使って、チェックの有無を判定
        if ($session->Judge('checkImage') && isset($session->Read('checkImage')[$_file])) {
            $checked = 'checked';
        } else {
            $checked = '';
        }
        // 画像を表示
        ViewImage($_file, $imageUrl, $_time, $checked);
        // ViewList($_file, $imageUrl, $checked);

        // バッファ出力
        if (ob_get_level() > 0) {
            ob_flush();
            flush();
        }
    }
    Output("</ul>", indentFlg:false);
    Output("</div>", indentFlg:false);

    Output("<div class='image-pager'>", indentFlg:false);
    ViewPager($params['max']);
    Output("</div>", indentFlg:false);
}

/**
 * ErrorSet
 * エラー文を定義する
 *
 * @param  mixed $errMsg
 *
 * @return void
 */
function ErrorSet($errMsg = ERROR_MESSAGE)
{
    $prevLink = new CustomTagCreate();
    $prevLink->SetTag('div', $errMsg, 'warning', true);
    $prevLink->ExecTag(true);
    $prevLink->SetHref("./", PUBLIC_PREVIOUS, 'page', true, '_self');
}
