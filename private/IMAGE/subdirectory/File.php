<?php

use PrivateTag\CustomTagCreate;

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
function MoldFile($file, String $fileName): array
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

function CheckType(string $inputType, string $targetType = 'image')
{
    $ret = true;
    if (!preg_match("/^{$targetType}/", $inputType)) {
        $ret = false;
    }

    return $ret;
}


/**
 * ImportImage
 * 画像をアップロードする
 *
 * @param  mixed $file
 *
 * @return array
 */
function ImportImage($upFiles): ?array
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
    // 不正アップロードされたパターン
    $result['illegal'] = [];
    $result['illegal']['count'] = 0;
    // ファイルがない
    // $result['no-file'] = [];
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

        // 不正経路でのアップロードの場合はエラー
        if (!is_uploaded_file($_files['tmp_name'])) {
            $result['illegal']['count']++;
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
 * GetImagePageName
 * 画像ページの種類を取得する
 *
 * @return string
 */
function GetImagePageName(): string
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

    $imgArray = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp'];

    $imgSrc = [];
    foreach ($imgArray as $_index) {
        $imgSrc[mb_strtolower($_index)] = IncludeFiles(AddPath(PUBLIC_IMAGE_DIR, $imagePageName), mb_strtolower($_index), true);
        $imgSrc[mb_strtoupper($_index)] = IncludeFiles(AddPath(PUBLIC_IMAGE_DIR, $imagePageName), mb_strtoupper($_index), true);
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
 * @param  string $order
 *
 * @return void
 */
function TimeSort(&$data, string $order = 'ASC')
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
        $time[] = $_data['time'];  // 時刻データを生成
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
function ValidParameter(array $data=[], bool $ajaxFlg=false)
{
    // 現在のページ番号の取得
    $page = GetPage();

    // 結果配列
    $result = null;
    if ($page <= 0 || $page === false) {
        if ($ajaxFlg === false) {
            Output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
            Output("<label class='all-check-label'><input type='checkbox' class='all-check-box' /><span class='check-word'>すべてチェックする</span></label>", indentFlg:false);
            Output("<div class='image-list'>", indentFlg:false);
            ErrorSet('ページの指定が不正です。');
            Output("</div><div class='image-pager'></div>", indentFlg:false);
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
            Output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
            Output("<label class='all-check-label'><input type='checkbox' class='all-check-box' /><span class='check-word'>すべてチェックする</span></label>", indentFlg:false);
            Output("<div class='image-list'>", indentFlg:false);
            ErrorSet('画像がありません。');
            Output("</div><div class='image-pager'></div>", indentFlg:false);
        }
        $result = ['result' => false, 'view-image-type' => GetImagePageName()];
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
function ChoiseImage(array $params, array $data): array
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
function ReadImage($ajaxFlg = false)
{

    // 現在選択している画像ページを取得
    $imagePageName = GetImagePageName();

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
    $params = ValidParameter($sortAray, $ajaxFlg);
    if (isset($params['result']) && $params['result'] === false) {
        return ['result' => false];
    }

    // 画像データを整理
    $sortAray = ChoiseImage($params, $sortAray, $ajaxFlg);

    if ($ajaxFlg === true) {
        return ShowImage($params, $sortAray, IMAGE_URL, $ajaxFlg);
    } else {
        ShowImage($params, $sortAray, IMAGE_URL);
    }
}

/**
 * ShowImage
 * 画像一覧を公開する
 *
 * @param array $params
 * @param array $data
 * @param string $imageUrl
 * @param boolean $ajaxFlg
 *
 * @return array|void
 */
function ShowImage(array $params, array $data, string $imageUrl, bool $ajaxFlg = false): ?array
{
    if ($ajaxFlg === true) {
        // 現在選択している画像ページを取得
        $imagePageName = GetImagePageName();
        $jsData = [];

        foreach ($data as $i => $_data) {
            $jsData[$i]['name'] = $_data['name'];
            // 画像サイズの取得
            $imageSize = AddPath(PUBLIC_IMAGE_DIR, $imagePageName, false);
            $imageSize = AddPath($imageSize, $_data['name'], false);
            $jsData[$i]['info'] = CalcImageSize($imageSize, \Private\Local\Word::$max_size);
            $jsData[$i]['time'] = date('Y/m/d H:i:s', $_data['time']);
        }

        $jsData['view-image-type'] = $imagePageName;
        $jsData['url'] = AddPath($imageUrl, $imagePageName, separator:'/');
        ;
        $jsData['pager'] = ViewPager($params['max'], $ajaxFlg);

        return $jsData;
    } else {
        Output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
        Output("<label class='all-check-label'><input type='checkbox' class='all-check-box' /><span class='check-word'>すべてチェックする</span></label>", indentFlg:false);

        // セッション開始
        if (!isset($session)) {
            $session = new PrivateSetting\Session();
        }

        // jQueryで書き換えれるように要素を追加
        Output("<div class='image-list'>", indentFlg:false);
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

        // コピーチェック用のセッションの削除
        if ($session->Judge('checkImage')) {
            $session->Delete('checkImage');
        }

        Output("</div>", indentFlg:false);

        Output("<div class='image-pager'>", indentFlg:false);
        ViewPager($params['max']);
        Output("</div>", indentFlg:false);
    }

    return null;
}

/**
 * ErrorSet
 * エラー文を定義する
 *
 * @param  string $errMsg
 *
 * @return void
 */
function ErrorSet(string $errMsg = ERROR_MESSAGE)
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
 * @return array
 */
function DeleteImage(): array
{
    $post = PrivateSetting\Setting::getPosts();
    $fileList = LoadAllImageFile();
    $imagePageName = GetImagePageName();

    $baseImageDir = AddPath(PUBLIC_IMAGE_DIR, $imagePageName);

    // _oldディレクトリがない場合はディレクトリを生成
    if (!is_dir(AddPath($baseImageDir, '_old'))) {
        mkdir(AddPath($baseImageDir, '_old'));
    }

    $ret = [];
    // 指定されたファイルをすべて削除 (退避ディレクトリに追加)
    foreach ($post as $post_key => $post_value) {
        if (preg_match('/^img_(.*)$/', $post_key)) {
            if (SearchData($post_value, $fileList)) {
                if (rename(AddPath($baseImageDir, $post_value, false), AddPath(AddPath($baseImageDir, '_old'), $post_value, false)) === true) {
                    $ret['success'][$post_key] = true;
                } else {
                    $ret['error'][$post_key] = false;
                }
            } else {
                $ret['error'][$post_key] = ILLEGAL_RESULT;
            }
        }
    }

    return $ret;
}

/**
 * CopyImage
 *画像をコピーする
 *
 * @param  array $upFilesArray
 *
 * @return array
 */
function CopyImage(array $upFilesArray): array
{
    $copyImageName = \PrivateSetting\Setting::GetPost('copy-image-name');

    // コピー結果
    $result = [];


    $directoryValid = ValidateData(PUBLIC_IMAGE_DIR, $copyImageName);
    if ($directoryValid === false) {
        // // 指定した画像ページがないパターン
        $result['not-page']['count'] = FAIL_COPY_IMAGE_COUNT;
        return $result;
    }

    // コピー元のディレクトリ名
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
    } elseif (count($upFilesArray) > IMAGE_COUNT_MAX) {
        // ファイル数が規定の条件を超えたパターン
        $result['count-over']['count']  = FAIL_COPY_IMAGE_COUNT;
        return $result;
    } else {
        // 不正なファイル名が混入しているパターン
        $result['illegal-value']['count'] = 0;
        foreach ($upFilesArray as $_key => $_file) {
            $fileValid = ValidateData(AddPath(PUBLIC_IMAGE_DIR, $srcImageName), $_file);
            if ($fileValid === false) {
                $result['illegal-value']['count']++;
                // 不正なファイル名を対象から外す
                unset($upFilesArray[$_key]);
            }
        }
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
