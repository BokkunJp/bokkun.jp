<?php
use PrivateTag\CustomTagCreate;

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

    // ファイル変数が配列でない場合は中断
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

function CheckType(string $inputType, string $targetType = 'image') {
    if (preg_match("/^{$targetType}/", $inputType)) {
        return true;
    }

    return false;
}


/**
 * ImportImage
 * 画像をアップロードする
 *
 * @param  mixed $file
 *
 * @return void
 */
function ImportImage($upFiles)
{
    $imageDir = PUBLIC_IMAGE_DIR;

    // データ成型
    $moldFiles = MoldFile($upFiles, 'all-files');

    // アップロード結果
    $result = [];
    // 成功パターン
    $result['success'] = [];
    $result['success']['count'] = 0;
    // ファイルの種類が違うパターン
    $result['-1'] = [];
    $result['-1']['count'] = 0;
    // ファイルサイズが0バイトのパターン
    $result['size'] = [];
    $result['size']['count'] = 0;
    // ファイルがない
    // $result['no-file'] = [];
    // tmpディレクトリがない
    // $result['tmp'] = [];
    // ファイルアップロード失敗パターン
    $result['other'] = [];
    $result['other']['count'] = 0;

    // ファイル数が規定の条件を超えた場合はアップロード中断
    if (count($moldFiles) > IMAGE_COUNT_MAX) {
        $result = IMAGE_COUNT_OVER;
        return $result;
    }

    foreach ($moldFiles as $_files) {
        if ($_files['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        // ファイルサイズが0バイトの場合はファイルサイズエラー
        if (!$_files['size']) {
            $result['size']['count']++;
            continue;
        }
        // アップロードされたファイルのTypeが画像用のものか調べる
        if (!CheckType($_files['type'])) {
            $result['-1']['count']++;
            continue;
        }

        if (!empty($_files) && $_files['error'] === UPLOAD_ERR_OK) {
            $imgType = FileExif($_files['tmp_name']);
        } else {
            switch ($_files['error']) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                case UPLOAD_ERR_PARTIAL:
                case UPLOAD_ERR_NO_TMP_DIR:
                case UPLOAD_ERR_CANT_WRITE:
                case UPLOAD_ERR_EXTENSION:
                    $result['other']['count']++;
                break;
                default:
                break;
            }
            continue;
        }

        // 画像ページタイプの取得
        $imagePageName = GetImagePageName();

        if (is_numeric($imgType)) {
            // 画像保管用のディレクトリがない場合は作成
            if (!file_exists(AddPath($imageDir, $imagePageName))) {
                mkdir(AddPath($imageDir, $imagePageName));
            }
            if (move_uploaded_file($_files['tmp_name'], AddPath(AddPath($imageDir, $imagePageName), $_files['name'], false))) {
                // $result['success'][$_files['name']] = true;
                $result['success']['count']++;
            } else {
                // $result['other'][$_files['name']] = false;
                $result['other']['count']++;
            }
        } else {
            // $result['-1'][$_files['name']] = -1;
            $result['-1']['count']++;
        }
    }


    return $result;
}

/**
 * SetImageType
 * 画像ページの種類を取得する
 *
 * @return string
 */
function GetImagePageName()
{
    // セッション開始
    if (!isset($session)) {
        $session = new PrivateSetting\Session();
    }

if (empty($session->Judge('image-view-directory'))) {
        $imagePageName = DEFAULT_IMAGE;
    } else {
        $imagePageName = $session->Read('image-view-directory');
    }

    return $imagePageName;
}

/**
 * LoadAllImageFile
 * 画像ファイル名を配列で一括取得する
 *
 * @return array
 */
function LoadAllImageFile()
{
    // 現在選択している画像ページを取得
    $imagePageName = GetImagePageName();

    $imgArray = ['png', 'jpg', 'jpeg', 'gif', 'bmp'];

    $imgSrc = [];
    foreach ($imgArray as $_index) {
        $imgSrc[mb_strtolower($_index)] = IncludeFiles(AddPath(PUBLIC_IMAGE_DIR , $imagePageName), mb_strtolower($_index), true);
        $imgSrc[mb_strtoupper($_index)] = IncludeFiles(AddPath(PUBLIC_IMAGE_DIR , $imagePageName), mb_strtoupper($_index), true);
    }

    $ret = [];
    foreach ($imgSrc as $_index => $_img) {
        if (isset($_img)) {
            foreach ($_img as $__val) {
                $ret[] = $__val;
            }
        }
    }

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
function ReadImage($readFlg = NOT_VIEW, $ajaxFlg = false)
{

    // 現在選択している画像ページを取得
    $imagePageName = GetImagePageName();

    if ($readFlg === NOT_VIEW) {
        if ($ajaxFlg === false) {
            Output('<p><a href="#update_page">一番下へ</a></p>');
            echo '現在、画像の公開を停止しています。';
        }
        return NOT_VIEW;
    } else {
        // アップロードされている画像データを読み込む
        $fileList = LoadAllImageFile();

        // ソート用にデータを調整
        $sortAray = array();
        foreach ($fileList as $index => $_file) {
            $sortAray[$index]['name'] = $_file;
            $sortAray[$index]['time'] = filemtime(AddPath(AddPath(PUBLIC_IMAGE_DIR , $imagePageName), $_file, false));
        }

        // 画像投稿日時の昇順にソート
        TimeSort($sortAray);

        if ($ajaxFlg === true) {
            return ShowImage($sortAray, IMAGE_URL, $ajaxFlg);
        } else {
            ShowImage($sortAray, IMAGE_URL);

        }
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
function ShowImage($data, $imageUrl, $ajaxFlg = false)
{

    // 現在のページ番号の取得
    $page = GetPage();

    if ($page <= 0 || $page === false) {
        if ($ajaxFlg === false) {
            Output('<p><a href="#update_page">一番下へ</a></p>');
            Output("<label class='all-check-label'><input type='checkbox' class='all-check-box' /><span class='check-word'>すべてチェックする</span></label>");
            Output("<div class='image-list'>");
            ErrorSet('ページの指定が不正です。');
            Output("</div>");
            Output("<div class='image-pager'>");
            Output("</div>");
        }
        return ['result' => false, 'view-image-type' => GetImagePageName()];
    } else {
        $start = ($page - 1) * GetCountPerPage();
    }
    $end = $start + GetCountPerPage();
    if ($end > count($data)) {
        $end = count($data);
    }

    if ($start >= $end) {
        if ($ajaxFlg === false) {
            Output('<p><a href="#update_page">一番下へ</a></p>');
            Output("<div class='image-list'>");
            ErrorSet('画像がありません。');
            Output("</div>");
            Output("<div class='image-pager'>");
            Output("</div>");
        }
        return ['result' => false, 'view-image-type' => GetImagePageName()];
    }

    if ($ajaxFlg === true) {
        // 現在選択している画像ページを取得
        $imagePageName = GetImagePageName();
        $jsData = [];

        for ($i = $start; $i < $end; $i++) {
            $jsData[$i]['name'] = $data[$i]['name'];
            $jsData[$i]['time'] = date('Y/m/d H:i:s', $data[$i]['time']);
        }

        $jsData['view-image-type'] = $imagePageName;
        $jsData['url'] = AddPath($imageUrl, $imagePageName, separator:'/');
;
        $jsData['pager'] = GetPagerForAjax($data);

        return $jsData;
    } else {
        Output('<p><a href="#update_page">一番下へ</a></p>');
        Output("<label class='all-check-label'><input type='checkbox' class='all-check-box' /><span class='check-word'>すべてチェックする</span></label>");

        // セッション開始
        if (!isset($session)) {
            $session = new PrivateSetting\Session();
        }

        // jQueryで書き換えれるように要素を追加
        Output("<div class='image-list'>");
        for ($i = $start; $i < $end; $i++) {
            $_file = $data[$i]['name'];
            $_time = $data[$i]['time'];

            // コピーチェック用のセッションを使って、チェックの有無を判定
            if ($session->Judge('checkImage') && isset($session->Read('checkImage')[$_file])) {
                $checked = 'checked';
            } else {
                $checked = '';
            }

            // 画像を表示
            ViewImage($_file, $imageUrl, $_time, $checked);
            // ViewList($_file, $imageUrl, $checked);
        }

        // コピーチェック用のセッションの削除
        if ($session->Judge('checkImage')) {
            $session->Delete('checkImage');
        }

        Output("</div>");

        Output("<div class='image-pager'>");
        ViewPager($data);
        Output("</div>");
    }
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
    $prevLink->SetHref("./", PRIVATE_PREVIOUS, 'page', true, '_self');

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
    $imagePageName = GetImagePageName();
    $count = 0;

    $baseImageDir = AddPath(PUBLIC_IMAGE_DIR, $imagePageName);

    // _oldディレクトリがない場合はディレクトリを生成
    if (!is_dir(AddPath($baseImageDir, '_old'))) {
        mkdir(AddPath($baseImageDir, '_old'));
    }
    // 指定されたファイルをすべて削除 (退避ディレクトリに追加)
    foreach ($post as $post_key => $post_value) {
        if ($post_key !== 'token' && $post_key !== 'delete') {
            $count++;
            if (in_array($post_value, $fileList)) {
                if (!rename(AddPath($baseImageDir, $post_value, false), AddPath(AddPath($baseImageDir, '_old'), $post_value, false)) === true) {
                    return false;
                }
            }
        }
    }
    return true;
}

/**
 * CopyImage
 *画像をコピーする
 *
 * @param  mixed $upFilesArray
 *
 * @return void|array
 */
function CopyImage($upFilesArray)
{
    $copyImageName = \PrivateSetting\Setting::GetPost('copy-image-name');

    // コピー結果
    $result = [];


    $defaultValid = ValidateData(PUBLIC_IMAGE_DIR, $copyImageName);
    if ($defaultValid === false) {
        // // 指定した画像ページがないパターン
        $result['not-page']['count'] = FAIL_COPY_IMAGE_COUNT;
        return $result;
    }

    // コピー元のファイル名
    $srcImageName = GetImagePageName();

    // 成功パターン
    $result['success'] = [];
    $result['success']['count'] = 0;
    // 失敗パターン
    $result['error'] = [];
    $result['error']['count'] = 0;

    // 画像が選択されていないパターン
    if (empty($upFilesArray)) {
        $result['no-select']['count']  = FAIL_COPY_IMAGE_COUNT;
        return $result;
    }

    // ファイル数が規定の条件を超えたパターン
    if (count($upFilesArray) > IMAGE_COUNT_MAX) {
        $result['count-over']['count']  = FAIL_COPY_IMAGE_COUNT;
        return $result;
    }

    // ファイル先とファイル元が同じ名称のパターン
    if ($srcImageName === $copyImageName) {
        $copyFilesArray = $upFilesArray;
        foreach ($copyFilesArray as $_key => $_file) {
            $tmpFileName = explode('.', $_file);
            $copyFilesArray[$_key] = $tmpFileName[0]. '_'. CreateRandom(IMAGE_NAME_CHAR_SIZE). '.'. $tmpFileName[1];
        }
    } else {
        $copyFilesArray = $upFilesArray;
    }
    // 各ファイル名にディレクトリパスを付与
    $srcImageName = AddPath(PUBLIC_IMAGE_DIR, $srcImageName);
    $copyImageName = AddPath(PUBLIC_IMAGE_DIR, $copyImageName);

    foreach ($upFilesArray as $_key => $_upFileName) {
        if (copy(AddPath($srcImageName, $_upFileName, false), AddPath($copyImageName, $copyFilesArray[$_key], false))) {
            $result['success']['count']++;
        } else {
            $result['error']['count']++;
        }
    }

    return $result;
}
