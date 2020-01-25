<?php

/**
 * GetPage
 * ページ番号を取得する
 *
 * @return void
 */
function GetPage() {
    $page = PublicSetting\Setting::GetQuery('page');
    if (is_null($page)) {
        $page = 1;
    } else if (!is_numeric($page)) {
        return false;
    }
    return (int) $page;
}

/**
 * ViewPager
 * ページ当たりの画像数を取得する
 * (post値が確認できない場合はデフォルト値を取得する)
 *
 * @param  void
 *
 * @return int
 */function GetPaging() {
    $session = new PublicSetting\Session();
    $post = PublicSetting\Setting::GetPost('image-value');
    if (isset($post) && is_numeric($post)) {
        $paging = (int) $post;
        // 上限設定
        if ($paging > (PAGING * MAX_VIEW)) {
            $paging = PAGING * MAX_VIEW;
        }
        $session->Write('image-view', $paging);
    } else if ($session->Judge('image-view')) {
        $paging = (int)$session->Read('image-view');
    } else {
        $paging = PAGING;
    }

    return $paging;

}

/**
 * ViewPager
 * ページングを表示する
 *
 * @param  mixed $file
 * @param  mixed $imageUrl
 *
 * @return void
 */
function ViewPager($file, $imageUrl) {
    $nowPage = GetPage();
    $paging = GetPaging();
    $maxPage = round(count($file) / $paging);
    if ($nowPage === false || $nowPage > $maxPage) {
        $page = 1;
    } else {
        $page = GetPage();
    }

    $pageHtml = new \PublicTag\CustomTagCreate();
    for ($_index = 1, $_vindex = 1; $_index < count($file); $_index += $paging, $_vindex++) {
        if ($_vindex === $page) {
            $pageHtml->TagSet('span', $_vindex . ' ', 'pager', true);
        } else {
            $pageHtml->SetHref("./FILE/?page={$_vindex}", $_vindex, 'page', false, '_self');
        }
        $pageHtml->TagExec(true);
        echo ' ';
    }
}
