<?php

/**
 * GetPage
 * 現在のページ番号を取得する
 *
 * @return int
 */
function GetPage() {
    $page = PublicSetting\Setting::GetQuery('page');
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
    $session = new PublicSetting\Session();
    $post = PublicSetting\Setting::GetPost('image-value');
    if (isset($post) && is_numeric($post)) {
        $pager = (int) $post;
        // 上限設定
        if ($pager > (PAGER * MAX_VIEW)) {
            $pager = PAGER * MAX_VIEW;
        }
        $session->Write('image-view', $pager);
    } else if ($session->Judge('image-view')) {
        $pager = (int)$session->Read('image-view');
    } else {
        $pager = PAGER;
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
function ViewPager($file) {
    $nowPage = GetPage();
    $pager = GetCountPerPage();
    $minPage = MIN_PAGE_COUNT;
    $maxPage = (int)ceil(count($file) / $pager);
    if ($maxPage === 0) {
        $maxPage = 1;
    }
    if ($nowPage === false || $nowPage > $maxPage) {
        $page = 1;
    } else {
        $page = GetPage();
    }

    $pageHtml = new \PublicTag\CustomTagCreate();

    for ($_index = MIN_PAGE_COUNT, $_vindex = MIN_PAGE_COUNT; $_index <= count($file); $_index += $pager, $_vindex++) {
        $pageValid = ValidateLoop($_vindex, $nowPage, $minPage, $maxPage);
        if ($pageValid === false) {
            $pageHtml->SetTag('span', $_vindex . ' ', 'pager');
            $pageHtml->ExecTag(true);
        } else if ($pageValid === true) {
            $pageHtml->SetHref("./?page={$_vindex}", $_vindex, 'pager', false, '_self');
            $pageHtml->ExecTag(true);
        }

        if ($pageValid === SPACE_ON && $_vindex !== $maxPage) {
            echo ' ... ';
        } else {
            echo ' ';
        }
}

    // 任意ページ番号入力フォーム
    SetInputForm($minPage, $maxPage);
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

function SetInputForm($minPage, $maxPage)
{
    Output("<input type='number' class='update_page' name='update_page' id='update_page' min=$minPage max=$maxPage />ページへ移動");
}
