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
 * ページングを表示する
 *
 * @param  mixed $file
 * @param  mixed $imageUrl
 *
 * @return void
 */
function ViewPager($file, $imageUrl) {
    $nowPage = GetPage();
    $maxPage = round(count($file) / PAGING);
    if ($nowPage === false || $nowPage > $maxPage) {
        $page = 1;
    } else {
        $page = GetPage();
    }

    $pageHtml = new \PublicTag\CustomTagCreate();
    for ($_index = 1, $_vindex = 1; $_index < count($file); $_index += PAGING, $_vindex++) {
        if ($_vindex === $page) {
            $pageHtml->TagSet('span', $_vindex . ' ', 'pager', true);
        } else {
            $pageHtml->SetHref("./FILE/?page={$_vindex}", $_vindex, 'page', false, '_self');
        }
        $pageHtml->TagExec(true);
        echo ' ';
    }
}
