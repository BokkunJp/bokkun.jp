<?php

use Private\Important\CustomTagCreate;

require_once('Page.php');
require_once('View.php');

/**
 * ImportImage
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
 * GetImagePageName
 * 画像ページの種類を取得する
 *
 * @return string
 */
function GetImagePageName(): string
{
    // セッション開始
    if (!isset($session)) {
        $session = new Private\Important\Session();
    }

    // debug($session->read());

    if (empty($session->judge('delete-image-view-directory'))) {
        $imagePageName = DEFAULT_IMAGE;
    } else {
        $imagePageName = $session->read('delete-image-view-directory');
    }

    return $imagePageName;
}

/**
 * LoadAllImageFile
 * 画像ファイル名を配列で一括取得する
 *
 * @return array
 */
function loadAllImageFile()
{
    // 現在選択している画像ページを取得
    $imagePageName = GetImagePageName();

    $imgArray = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp', 'mp4'];

    $imgSrc = [];
    $imgPath = new \Path(PUBLIC_IMAGE_DIR);
    $imgPath->add($imagePageName);
    $imgPath->add('_oldImage');
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
 * TimeSort
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
        return ['result' => false, 'view-image-type' => GetImagePageName()];
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
        $result = ['result' => false, 'view-image-type' => GetImagePageName()];
    }

    if (!isset($result)) {
        $result = ['start' => $start, 'end' => $end, 'max' => count(loadAllImageFile())];
    }

    return $result;
}

/**
 * ChoiceImage
 * 全画像データのうち、表示に必要なデータのみを抽出する
 *
 * @param array $params
 * @param array $data
 * @return array
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
 * ReadImage
 * 画像を読み込み、公開する
 *
 * @param  mixed $read_flg
 *
 * @return void
 */
function readImage($ajaxFlg = false)
{

    // 現在選択している画像ページを取得
    $imagePageName = GetImagePageName();

    // 削除されている画像データを読み込む
    $fileList = loadAllImageFile();

    // ソート用にデータを調整
    $sortAray = array();
    foreach ($fileList as $index => $_file) {
        $sortAray[$index]['name'] = $_file;
        $imagePath = new \Path(PUBLIC_IMAGE_DIR);
        $imagePath->addArray([$imagePageName, '_oldImage', $_file]);
        $sortAray[$index]['time'] = filemtime($imagePath->get());
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
function showImage(
    array $params,
    array $data,
    string $imageUrl,
    bool $ajaxFlg = false
): ?array {
    if ($ajaxFlg === true) {
        // 現在選択している画像ページを取得
        $imagePageName = GetImagePageName();
        $jsData = [];

        foreach ($data as $i => $_data) {
            $jsData[$i]['name'] = $_data['name'];
            // 画像データの取得
            $imagePath = new \Path(PUBLIC_IMAGE_DIR);
            $imagePath->add($imagePageName);
            $imagePath->setPathEnd();
            $imagePath->add($_data['name']);
            $imagePath = $imagePath->get();
            $jsData[$i]['info'] = calcImageSize($imagePath, (int)GetIni('private', 'ImageMaxSize'));
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
        output("<label class='all-check-label'><input type='checkbox' class='all-check-box' /><span class='check-word'>すべてチェックする</span></label>", indentFlg:false);

        // セッション開始
        if (!isset($session)) {
            $session = new Private\Important\Session();
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
            ViewImage($_file, $imageUrl, $_time, $checked);
            // ViewList($_file, $imageUrl, $checked);

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
 * ErrorSet
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
 * ValidateDeleteImage
 *
 * 削除するファイル群や削除対象のファイルが存在するかチェック
 *
 * @param array $listImages
 * @param array|string $target
 *
 * @return boolean
 */
function ValidateDeleteImage(
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
 * DeleteImage
 * 画像を一括で完全に削除する
 *
 * @param array
 *
 * @return array
 */
function DeleteImages(array $deleteImages): array
{
    $imagePageName = GetImagePageName();

    $baseImageDir = new \Path(PUBLIC_IMAGE_DIR);
    $baseImageDir->add($imagePageName);

    $oldImageDir = new \Path($baseImageDir->get());
    $oldImageDir->add('_oldImage');

    $ret = [];
    // 指定されたファイルをすべて削除
    foreach ($deleteImages as $_key => $_value) {
        $deleteImagePath = new \Path($oldImageDir);
        $deleteImagePath->setPathEnd();
        $deleteImagePath->add($_value);
        if ($_value !== false && preg_match('/^img_(.*)$/', $_key)
        && searchData($_value, scandir($oldImageDir->get()))
        && unlink($deleteImagePath->get()) === true
        ) {
            $ret['success'][$_key] = true;
        } else {
            $ret['error'][$_key] = true;
        }
    }

    return $ret;
}

/**
 * RestoreImages
 * 画像を復元する
 *
 * @param  array $restoreFilesArray
 *
 * @return array
 */
function RestoreImages(array $restoreFilesArray): array
{
    $restoreImageName = GetImagePageName();

    $baseImageDir = new \Path(PUBLIC_IMAGE_DIR);
    $baseImageDir->add($restoreImageName);

    $deleteImageDir = new \Path($baseImageDir->get());
    $deleteImageDir->add('_oldImage');

    // リストア結果
    $result = [];

    // 成功パターン
    $result['success'] = [];
    $result['success']['count'] = 0;
    // 失敗パターン
    $result['error'] = [];
    $result['error']['count'] = 0;

    // 画像が選択されていないパターン
    if (empty($restoreFilesArray)) {
        $result['no-select']['count']  = FAIL_RESTORE_IMAGE_COUNT;
        return $result;
    } elseif (count($restoreFilesArray) > IMAGE_COUNT_MAX) {
        // ファイル数が規定の条件を超えたパターン
        $result['count-over']['count']  = FAIL_RESTORE_IMAGE_COUNT;
        return $result;
    } else {
        // 不正なファイル名が混入しているパターン
        $result['illegal-value']['count'] = 0;

        // 復元前のディレクトリパス
        $srcDirectoryPath = new \Path(PUBLIC_IMAGE_DIR);
        $srcDirectoryPath->add($restoreImageName);
        $srcDirectoryPath->add('_oldImage');
        $srcDirectoryPath->setPathEnd();
        foreach ($restoreFilesArray as $_key => $_file) {
            $srcImagePath = new \Path($srcDirectoryPath);
            $fileValid = ValidateData($srcImagePath->get(), $_file);
            if ($fileValid === false) {
                $result['illegal-value']['count']++;
                // 不正なファイル名を対象から外す
                unset($restoreFilesArray[$_key]);
            }
        }
    }

    // 復元対象のディレクトリパス
    $restoreImagePath = new \Path(PUBLIC_IMAGE_DIR);
    $restoreImagePath->add($restoreImageName);
    $restoreImagePath->setPathEnd();

    foreach ($restoreFilesArray as $_key => $_upFileName) {
        if (rename($srcDirectoryPath->add($_upFileName, false), $restoreImagePath->add($_upFileName, false))) {
            $result['success']['count']++;
        } else {
            $result['error']['count']++;
        }
    }

    return $result;
}
