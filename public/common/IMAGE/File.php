<?php

use Public\Important\CustomTagCreate;

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

/**
 * LoadAllImageFile
 * 画像ファイル名を配列で一括取得する
 *
 * @return array
 */
function loadAllImageFile()
{

    // 現在アクセスしているページ名を取得
    $imagePageName = basename(getcwd());


    $imgArray = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp', 'mp4'];

    $imgSrc = [];
    $imagePageNamePath = new \Path(PUBLIC_IMAGE_DIR);
    $imagePageNamePath->add($imagePageName);
    foreach ($imgArray as $_index) {
        $imgSrc[mb_strtolower($_index)] = includeFiles($imagePageNamePath->get(), mb_strtolower($_index), true);
        $imgSrc[mb_strtoupper($_index)] = includeFiles($imagePageNamePath->get(), mb_strtoupper($_index), true);
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
function validateParameter(array $data=[], bool $ajaxFlg=false)
{
    // 現在のページ番号の取得
    $page = getPage();

    // 結果配列
    $result = null;
    if ($page <= 0 || $page === false) {
        if ($ajaxFlg === false) {
            output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
            output("<div class='image-box'>", indentFlg:false);
            setError('ページの指定が不正です。');
            output("</div><div class='image-pager'></div>", indentFlg:false);
        }
        return ['result' => false, 'view-image-type' => basename(getcwd())];
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
            output("<div class='image-box'>", indentFlg:false);
            setError('現在の枚数表示では、そのページには画像はありません。');
            output("</div><div class='image-pager'></div>", indentFlg:false);
        }
        $result = ['result' => false, 'view-image-type' => basename(getcwd())];
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

    // 現在のページを取得
    $imagePageName = basename(getcwd());

    // アップロードされている画像データを読み込む
    $fileList = loadAllImageFile();

    // ソート用にデータを調整
    $sortAray = array();
    foreach ($fileList as $index => $_file) {
        $sortAray[$index]['name'] = $_file;

        $imagePath = new \Path(PUBLIC_IMAGE_DIR);
        $imagePath->addArray([$imagePageName, $_file]);
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
        $imagePageName = basename(getcwd());
        $jsData = [];

        foreach ($data as $i => $_data) {
            $jsData[$i]['name'] = $_data['name'];
            // 画像データの取得
            $imagePath = new \Path(PUBLIC_IMAGE_DIR);
            $imagePath->addArray([$imagePageName, $_data['name']]);

            $jsData[$i]['info'] = calcImageSize($imagePath->get(), (int)GetIni('Public', 'ImageMaxSize'));
            $jsData[$i]['time'] = date('Y/m/d H:i:s', $_data['time']);
            // 画像データが取得できなかった場合は、配列の該当データの削除
            if ($jsData[$i]['info'] === false) {
                unset($jsData[$i]);
            }
        }

        $jsData['view-image-type'] = $imagePageName;
        $jsUrl = new \Path($imageUrl, '/');
        $jsUrl->add($imagePageName);
        $jsData['url'] = $jsUrl->get();
        ;
        $jsData['pager'] = viewPager($params['max'], $ajaxFlg);

        return $jsData;
    } else {
        // jQueryで書き換えれるように要素を追加
        output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
        output("<div class='image-box'  ontouchstart=''>", indentFlg:false);

        output("<ul>", indentFlg:false);
        foreach ($data as $i => $_data) {
            $_file = $_data['name'];
            $_time = $_data['time'];

            // 画像を表示
            ViewImage($_file, $imageUrl, $_time);
            // ViewList($_file, $imageUrl);

            // バッファ出力
            if (ob_get_level() > 0) {
                ob_flush();
                flush();
            }
        }

        output("</ul>", indentFlg:false);
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
    $prevLink->setHref("./", PUBLIC_PREVIOUS, 'page', true, '_self');
}
