<?php

use Private\Important\CustomTagCreate;

require_once('Page.php');
require_once('View.php');

/**
 * importImage
 * ファイルデータを成型する
 *
 * @param  array $file
 * @param  string $fileName
 *
 * @return array
 */
function moldFile(array $file, String $fileName): array
{
    $moldFiles = [];

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
 * importImage
 * 画像をアップロードする
 *
 * @param  array $file
 *
 * @return array
 */
function importImage(array $upFiles): ?array
{
    // データ成型
    $moldFiles = moldFile($upFiles, 'all-files');

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
        // アップロードされたファイルのTypeが画像または動画用のものか調べる
        if (!CheckType($_files['type']) && !CheckType($_files['type'], 'video')) {
            $result['-1']['count']++;
            continue;
        }

        // 不正経路でのアップロードの場合はエラー
        if (!is_uploaded_file($_files['tmp_name'])) {
            $result['illegal']['count']++;
            continue;
        }

        if (!empty($_files) && $_files['error'] === UPLOAD_ERR_OK) {
            $imgType = exif_imagetype($_files['tmp_name']);
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
        $imagePageName = getImagePageName();

        if (is_numeric($imgType)) {
            // 画像保管用のディレクトリがない場合は作成
            $imageDir = new \Path(PUBLIC_DIR_LIST['image']);
            $imageDir->add($imagePageName);
            $imageDir = $imageDir->get();
            if (!file_exists($imageDir)) {
                mkdir($imageDir);
            }
            $file = new \Path($imageDir);
            $file->setPathEnd();
            $file->add($_files['name']);
            if (move_uploaded_file($_files['tmp_name'], $file->get())) {
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
 * getImagePageName
 * 画像ページの種類を取得する
 *
 * @return string
 */
function getImagePageName(): string
{
    // セッション開始
    if (!isset($session)) {
        $session = new Private\Important\Session('private-image');
    }

    if (empty($session->judge('image-view-directory'))) {
        $imagePageName = DEFAULT_IMAGE;
    } else {
        $imagePageName = $session->read('image-view-directory');
    }

    return $imagePageName;
}

/**
 * loadAllImageFile
 * 画像ファイル名を配列で一括取得する
 *
 * @return array
 */
function loadAllImageFile()
{
    // 現在選択している画像ページを取得
    $imagePageName = getImagePageName();

    $imgArray = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp', 'mp4'];

    $imgSrc = [];
    $imgPath = new \Path(PUBLIC_DIR_LIST['image']);
    $imgPath->add($imagePageName);
    foreach ($imgArray as $_index) {
        $imgSrc[mb_strtolower($_index)] = includeFiles($imgPath->get(), mb_strtolower($_index), true);
        $imgSrc[mb_strtoupper($_index)] = includeFiles($imgPath->get(), mb_strtoupper($_index), true);
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
 * sortTime
 * 配列を日時でソートする
 *
 * @param  mixed $data
 * @param  string $order
 *
 * @return void
 */
function sortTime(&$data, string $order = 'ASC')
{
    if (is_array($data) == false) {
        throw new Exception('データは配列でなければいけません。');
        return -1;
    }

    $time = [];
    foreach ($data as $_data) {
        // データ内に必要な要素があるかチェック
        if (array_key_exists('time', $_data) == false) {
            throw new Exception('必要な要素がありません。');
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
        throw new Exception('順序指定が不正です。');
        return -1;
    }

    array_multisort($time, $sort, $data);
}

/**
 * validateParameter
 * ページ関係の内容の検証
 *
 * @param array $data
 * @param boolean $ajaxFlg
 * @return array
 */
function validateParameter(array $data=[], bool $ajaxFlg=false)
{
    // 現在のページ番号の取得
    $page = getPage();

    // 結果配列
    $result = null;
    if ($page <= 0 || $page === false) {
        if ($ajaxFlg === false) {
            output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
            output("<label class='all-check-label'><input type='checkbox' class='all-check-box' /><span class='check-word'>すべてチェックする</span></label>", indentFlg:false);
            output("<div class='image-list'>", indentFlg:false);
            setError('ページの指定が不正です。');
            output("</div><div class='image-pager'></div>", indentFlg:false);
        }
        return ['result' => false, 'view-image-type' => getImagePageName()];
    } else {
        $start = ($page - 1) * getCountPerPage();
    }
    $end = $start + getCountPerPage();
    if ($end > count($data)) {
        $end = count($data);
    }

    if ($start >= $end) {
        if ($ajaxFlg === false) {
            output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
            output("<label class='all-check-label'><input type='checkbox' class='all-check-box' /><span class='check-word'>すべてチェックする</span></label>", indentFlg:false);
            output("<div class='image-list'>", indentFlg:false);
            setError('現在の枚数表示では、そのページには画像はありません。');
            output("</div><div class='image-pager'></div>", indentFlg:false);
        }
        $result = ['result' => false, 'view-image-type' => getImagePageName()];
    }

    if (!isset($result)) {
        $result = ['start' => $start, 'end' => $end, 'max' => count(loadAllImageFile())];
    }

    return $result;
}

/**
 * choiceImage
 * 全画像データのうち、表示に必要なデータのみを抽出する
 *
 * @param array $params
 * @param array $data
 * @return void
 */
function choiceImage(array $params, array $data): array
{
    // 結果用配列
    $cloneImg = [];
    for ($i = $params['start'];$i < $params['end']; $i++) {
        $cloneImg[$i] = $data[$i];
    }

    return $cloneImg;
}

/**
 * readImage
 * 画像を読み込み、公開する
 *
 * @param  mixed $read_flg
 *
 * @return void
 */
function readImage($ajaxFlg = false)
{

    // 現在選択している画像ページを取得
    $imagePageName = getImagePageName();

    // アップロードされている画像データを読み込む
    $fileList = loadAllImageFile();

    // ソート用にデータを調整
    $sortAray = array();
    $imgPath = new \Path(PUBLIC_DIR_LIST['image']);
    $imgPath->add($imagePageName);
    foreach ($fileList as $index => $_file) {
        $sortAray[$index]['name'] = $_file;
        $filePath = new \Path($imgPath->get());
        $filePath->setPathEnd();
        $filePath->add($_file);
        $sortAray[$index]['time'] = filemtime($filePath->get());
    }

    // 画像投稿日時の昇順にソート
    sortTime($sortAray);

    // ページ関連で必要なデータの検証
    $params = validateParameter($sortAray, $ajaxFlg);
    if (isset($params['result']) && $params['result'] === false) {
        return ['result' => false, 'view-image-type' => $imagePageName];
    }

    // 画像データを整理
    $sortAray = choiceImage($params, $sortAray, $ajaxFlg);

    if ($ajaxFlg === true) {
        return showImage($params, $sortAray, IMAGE_URL, $ajaxFlg);
    } else {
        showImage($params, $sortAray, IMAGE_URL);
    }
}

/**
 * showImage
 * 画像一覧を公開する
 *
 * @param array $params
 * @param array $data
 * @param string $imageUrl
 * @param boolean $ajaxFlg
 *
 * @return array|void
 */
function showImage(
    array $params,
    array $data,
    string $imageUrl,
    bool $ajaxFlg = false
): ?array {
    if ($ajaxFlg === true) {
        // 現在選択している画像ページを取得
        $imagePageName = getImagePageName();
        $jsData = [];

        foreach ($data as $i => $_data) {
            $jsData[$i]['name'] = $_data['name'];
            // 画像データの取得
            $imagePath = new \Path(PUBLIC_DIR_LIST['image']);
            $imagePath->add($imagePageName);
            $imagePath->setPathEnd();
            $imagePath->add($_data['name']);
            $imagePath = $imagePath->get();
            $jsData[$i]['info'] = calcImageSize($imagePath, (int)getIni('private', 'ImageMaxSize'));
            $jsData[$i]['time'] = date('Y/m/d H:i:s', $_data['time']);
            // 画像データが取得できなかった場合は、配列の該当データの削除
            if ($jsData[$i]['info'] === false) {
                unset($jsData[$i]);
            }
        }

        $jsData['view-image-type'] = $imagePageName;
        $imageUrl = new \Path($imageUrl, '/');
        $imageUrl->add($imagePageName);
        $jsData['url'] = $imageUrl->get();
        ;
        $jsData['pager'] = viewPager($params['max'], $ajaxFlg);

        return $jsData;
    } else {
        output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
        output('<span><a href="">Zipファイル生成</a></span>');
        output("<label class='all-check-label'><input type='checkbox' class='all-check-box' /><span class='check-word'>すべてチェックする</span></label>", indentFlg:false);

        // セッション開始
        if (!isset($session)) {
            $session = new Private\Important\Session('private-image');
        }

        // jQueryで書き換えれるように要素を追加
        output("<div class='image-list'>", indentFlg:false);
        foreach ($data as $i => $_data) {
            $_file = $_data['name'];
            $_time = $_data['time'];

            // コピーチェック用のセッションを使って、チェックの有無を判定
            if ($session->judge('checkImage') && isset($session->read('checkImage')[$_file])) {
                $checked = 'checked';
            } else {
                $checked = '';
            }

            // 画像を表示
            viewImage($_file, $imageUrl, $_time, $checked);
            // viewList($_file, $imageUrl, $checked);

            // バッファ出力
            if (ob_get_level() > 0) {
                ob_flush();
                flush();
            }
        }

        // コピーチェック用のセッションの削除
        if ($session->judge('checkImage')) {
            $session->delete('checkImage');
        }

        output("</div>", indentFlg:false);

        output("<div class='image-pager'>", indentFlg:false);
        viewPager($params['max']);
        output("</div>", indentFlg:false);
    }

    return null;
}

/**
 * setError
 * エラー文を定義する
 *
 * @param  string $errMsg
 *
 * @return void
 */
function setError(string $errMsg = ERROR_MESSAGE)
{
    $prevLink = new CustomTagCreate();
    $prevLink->setTag('div', $errMsg, 'warning', true);
    $prevLink->execTag(true);
    $prevLink->setHref("./", PRIVATE_PREVIOUS, 'page', true, '_self');
}
/**
 * validateDeleteImage
 *
 * 削除するファイル群や削除対象のファイルが存在するかチェック
 *
 * @param array $listImages
 * @param array|string $target
 *
 * @return boolean
 */
function validateDeleteImage(
    array|string $target,
    array $listImages = [],
): bool {
    $ret = false;

    if (!is_array($target)) {
        $ret = searchData($target, $listImages);
    } else {
        foreach ($target as $_key => $_value) {
            if (preg_match('/^img_(.*)$/', $_key)) {
                $ret = true;
            }
        }
    }

    return $ret;
}

/**
 * deleteImages
 * 画像を一括削除する
 *
 * @param array
 *
 * @return array
 */
function deleteImages(array $deleteImages): array
{
    $imagePageName = getImagePageName();

    $baseImageDir = new \Path(PUBLIC_DIR_LIST['image']);
    $baseImageDir->add($imagePageName);

    $oldImageDir = new \Path($baseImageDir->get());
    $oldImageDir->add('_oldImage');

    // _oldImageディレクトリがない場合はディレクトリを生成
    if (!is_dir($oldImageDir->get())) {
        mkdir($oldImageDir->get());
    }

    $ret = [];
    // 指定されたファイルをすべて削除 (退避ディレクトリに追加)
    foreach ($deleteImages as $_key => $_value) {
        $file = new \Path($baseImageDir->get());
        $file->setPathEnd();
        $file->add($_value);
        $oldFile = new \Path($oldImageDir->get());
        $oldFile->setPathEnd();
        $oldFile->add($_value);
        if ($_value !== false && preg_match('/^img_(.*)$/', $_key)
        && searchData($_value, scandir($baseImageDir->get()))
        && rename($file->get(), $oldFile->get()) === true
        ) {
            $ret['success'][$_key] = $_value;
        } else {
            $ret['error'][$_key] = $_value;
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
    $copyImageName = \Private\Important\Setting::getPost('copy-image-name');

    // コピー結果
    $result = [];


    $directoryValid = validateData(PUBLIC_DIR_LIST['image'], $copyImageName);
    if ($directoryValid === false) {
        // // 指定した画像ページがないパターン
        $result['not-page']['count'] = FAIL_COPY_IMAGE_COUNT;
        return $result;
    }

    // コピー元のディレクトリ名
    $srcImageName = getImagePageName();

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
            $srcImagePath = new \Path(PUBLIC_DIR_LIST['image']);
            $srcImagePath->add($srcImageName);
            $fileValid = validateData($srcImagePath->get(), $_file);
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
            $copyFilesArray[$_key] = $tmpFileName[0]. '_'. bin2hex(openssl_random_pseudo_bytes(IMAGE_NAME_CHAR_SIZE)) . '.'. $tmpFileName[1];
        }
    } else {
        $copyFilesArray = $upFilesArray;
    }
    // 各ファイル名にディレクトリパスを付与
    $srcImagePath = new \Path(PUBLIC_DIR_LIST['image']);
    $srcImagePath->add($srcImageName);
    $srcImageName = $srcImagePath->get();
    $srcImagePath = new \Path(PUBLIC_DIR_LIST['image']);
    $srcImagePath->add($copyImageName);
    $copyImageName = $srcImagePath->get();

    foreach ($upFilesArray as $_key => $_upFileName) {
        $upFilePath = new \Path($srcImageName);
        $upFilePath->setPathEnd();
        $upFilePath->add($_upFileName);

        $copyFilePath = new \Path($copyImageName);
        $copyFilePath->setPathEnd();
        $copyFilePath->add($copyFilesArray[$_key]);
        if (copy($upFilePath->get(), $copyFilePath->get())) {
            $result['success']['count']++;
        } else {
            $result['error']['count']++;
        }
    }

    return $result;
}
