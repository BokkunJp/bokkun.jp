<?php

use PublicTag\CustomTagCreate;

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
function MoldFile(array $file, String $fileName): array
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
function LoadAllImageFile()
{

    // 現在アクセスしているページ名を取得
    $imagePageName = basename(getcwd());


    $imgArray = ['png', 'jpg', 'jpeg', 'gif', 'bmp', 'webp', 'mp4'];

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
    $params = ValidParameter($sortAray, $ajaxFlg);
    if (isset($params['result']) && $params['result'] === false) {
        return ['result' => false, 'view-image-type' => $imagePageName];
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
function ShowImage(
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
            $imagePath = AddPath(PUBLIC_IMAGE_DIR, $imagePageName, false);
            $imagePath = AddPath($imagePath, $_data['name'], false);
            $jsData[$i]['info'] = CalcImageSize($imagePath, (int)GetIni('Public', 'ImageMaxSize'));
            $jsData[$i]['time'] = date('Y/m/d H:i:s', $_data['time']);
            // 画像データが取得できなかった場合は、配列の該当データの削除
            if ($jsData[$i]['info'] === false) {
                unset($jsData[$i]);
            }
        }

        $jsData['view-image-type'] = $imagePageName;
        $jsData['url'] = AddPath($imageUrl, $imagePageName, separator:'/');
        ;
        $jsData['pager'] = ViewPager($params['max'], $ajaxFlg);

        return $jsData;
    } else {
        // jQueryで書き換えれるように要素を追加
        Output('<p><a href="#update_page">一番下へ</a></p>', indentFlg:false);
        Output("<div class='image-box'  ontouchstart=''>", indentFlg:false);

        Output("<ul>", indentFlg:false);
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

        Output("</ul>", indentFlg:false);
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
    $prevLink->SetHref("./", PUBLIC_PREVIOUS, 'page', true, '_self');
}
