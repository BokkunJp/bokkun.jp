<?php

/**
 * GetPage
 *
 * ページ番号を取得する。
 *
 * @param void
 *
 * @return integer|false
 */
function GetPage(): int|false
{
    $page = public\Setting::GetQuery('page');
    if ($page === false) {
        $page = 1;
    } elseif (!is_numeric($page)) {
        return false;
    }
    return (int) $page;
}

/**
 * GetCountPerPage
 *
 * ページ当たりの画像数を取得する。
 * (post値が確認できない場合はデフォルト値を取得する)
 *
 * @param  void
 *
 * @return integer
 */
function GetCountPerPage()
{
    $session = new public\Session();
    $post = public\Setting::GetPost('image-value');
    if (isset($post) && is_numeric($post)) {
        $pager= (int) $post;
        // 上限設定
        if ($pager > (PAGER * MAX_VIEW)) {
            $pager = PAGER * MAX_VIEW;
        }
        $session->Write('image-view', $pager);
    } elseif ($session->Judge('image-view')) {
        $pager = (int)$session->Read('image-view');
    } else {
        $pager = PAGER;
    }

    return $pager;
}

/**
 * ViewPager
 *
 * ページャーを表示する。
 *
 * @param  int $max
 * @param boolean $ajaxFlg
 *
 *  @return string
 */
function ViewPager($max, $ajaxFlg = false)
{
    $htmlVal = '';
    $nowPage = GetPage();
    $pager = GetCountPerPage();
    $minPage = MIN_PAGE_COUNT;
    $maxPage = (int)ceil(bcdiv($max, $pager, COUNT_RECURSIVE));       // 最大ページ(画像数をページ数で割って丸める。精度の問題から除算にはBCMathライブラリのbcdivを使用)

    $pageHtml = new \PublicTag\CustomTagCreate();

    for ($_index = MIN_PAGE_COUNT, $_vindex = MIN_PAGE_COUNT; $_index <= $max; $_index += $pager, $_vindex++) {
        $pageValid = ValidateLoop($_vindex, $nowPage, $minPage, $maxPage);
        if ($pageValid === false) {
            $pageHtml->SetTag('span', $_vindex . ' ', 'pager', true);
        } elseif ($pageValid === true) {
            $pageHtml->SetHref("./?page={$_vindex}", $_vindex, 'pager', false, '_self');
        }

        if ($pageValid === true && $_vindex !== $minPage && $_vindex !== $maxPage) {
            if ($ajaxFlg) {
                $htmlVal .= ' ';
            } else {
                echo ' ';
            }
        }

        // Ajaxか画面表示かで出力形式を変える (HTMLに情報をセットしたときのみ出力)
        if (is_bool($pageValid)) {
            if ($ajaxFlg) {
                $htmlVal .= $pageHtml->ExecTag();
            } else {
                $pageHtml->ExecTag(true);
            }
        }

        if ($pageValid === SPACE_ON && $_vindex !== $maxPage) {
            if ($ajaxFlg) {
                $htmlVal .= '...';
            } else {
                echo '...';
            }
        } elseif ($_vindex !== $maxPage) {
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
 *
 * Pagenatorのページ数を表示するかを判定する。
 *
 * @param integer $nowPage
 * @param integer $minPage
 * @param integer $maxPage
 *
 * @return void
 */
function ValidateLoop($currentPage, $nowPage, $minPage, $maxPage)
{
    switch ($currentPage) {
        case $minPage:
        case $maxPage:
            if ($nowPage === $currentPage) {
                $valid = false;
                break;
            }
            // no break
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

/**
 * SetInputForm
 *
 * ページ移動フォームの生成。

 * @param integer $minPage
 * @param integer $maxPage
 * @param boolean $ajaxFlg
 * @return string|bool
 */
function SetInputForm(int $minPage, int $maxPage, bool $ajaxFlg = false): string|bool
{
    $htmlVal = "<span class='image-page-input'><input type='number' class='update_page' name='update_page' id='update_page' min=$minPage max=$maxPage />ページへ<button name='move'>移動</button></span>";
    if ($ajaxFlg) {
        return $htmlVal;
    } else {
        print_r($htmlVal);
        return true;
    }
}
