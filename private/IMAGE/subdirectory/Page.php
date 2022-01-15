<?php

/**
 * GetPage
 * ページ番号を取得する
 *
 * @return void
 */
function GetPage() {
    $page = PrivateSetting\Setting::GetQuery('page');
    if ($page === false) {
        $page = 1;
    } else if (!is_numeric($page)) {
        return false;
    }
    return (int) $page;
}

/**
 * GetCountPerPage
 * ページ当たりの画像数を取得する
 * (post値が確認できない場合はデフォルト値を取得する)
 *
 * @param  void
 *
 * @return int
 */function GetCountPerPage() {
    $session = new PrivateSetting\Session();
    $post = PrivateSetting\Setting::GetPost('image-value');
    if (isset($post) && is_numeric($post)) {
        $pager= (int) $post;
        // 上限設定
        if ($pager> (PAGER * MAX_VIEW)) {
            $pager= PAGER * MAX_VIEW;
        }
        $session->Write('image-view', $pager);
    } else if ($session->Judge('image-view')) {
        $pager= (int)$session->Read('image-view');
    } else {
        $pager= PAGER;
    }

    return $pager;

}

/**
 * ViewPager
 * ページャーを表示する
 *
 * @param  mixed $file
 *
 * @return void
 */
function ViewPager($file, $ajaxFlg = false) {
    $htmlVal = '';
    $nowPage = GetPage();
    $pager = GetCountPerPage();
    $minPage = MIN_PAGE_COUNT;
    $maxPage = gmp_strval(gmp_div(count($file), $pager, GMP_ROUND_PLUSINF));       // 最大ページ(画像数をページ数で割って丸める。精度の問題から除算にはGMPを使用)

    $pageHtml = new \PrivateTag\CustomTagCreate();

    for ($_index = MIN_PAGE_COUNT, $_vindex = MIN_PAGE_COUNT; $_index <= count($file); $_index += $pager, $_vindex++) {
        $pageValid = ValidateLoop($_vindex, $nowPage, $minPage, $maxPage);
        if ($pageValid === false) {
            $pageHtml->SetTag('span', $_vindex . ' ', 'pager', true);
        } else if ($pageValid === true) {
            $pageHtml->SetHref("./?page={$_vindex}", $_vindex, 'pager', false, '_self');
        }

        // Ajaxか画面表示かで出力形式を変える
        if ($ajaxFlg) {
            $htmlVal .= $pageHtml->ExecTag();
        } else {
            $pageHtml->ExecTag(true);
        }

        if ($pageValid === SPACE_ON && $_vindex !== $maxPage) {
            if ($ajaxFlg) {
                $htmlVal .= '...';
            } else {
                echo '...';
            }
        } else {
            if ($ajaxFlg) {
                $htmlVal .= ' ';
            } else {
                echo ' ';
            }
        }
    }
    // 任意ページ番号入力フォーム
    $htmlVal .= SetInputForm($minPage, $maxPage, $ajaxFlg);

    if ($ajaxFlg) {
        return $htmlVal;
    }
}

/**
 * ValidateLoop
 * Pagenatorのページ数を表示するかを判定する
 *
 * @param  int $nowPage
 * @param  int $minPage
 * @param  int $maxPage
 * @return void
 */
function ValidateLoop($currentPage, $nowPage, $minPage, $maxPage) {
    switch ($currentPage) {
        case $minPage:
        case $maxPage:
            if ($nowPage === $currentPage) {
                $valid = false;
                break;
            }
        case $nowPage - 1:
        case $nowPage + 1:
            $valid = true;
        break;
        case $nowPage - 2:
        case $nowPage + 2:
            $valid = SPACE_ON;
        break;
        case $nowPage:
            $valid = false;
        break;
    default:
            $valid = null;
        break;
    }

    return $valid;
}

function SetInputForm($minPage, $maxPage, $ajaxFlg = false) {
    $htmlVal = "<input type='number' class='update_page' name='update_page' id='update_page' min=$minPage max=$maxPage />ページへ<button name='move'>移動</button>";
    if ($ajaxFlg) {
        return $htmlVal;
    } else {
    print_r($htmlVal);
    return true;
    }
}
