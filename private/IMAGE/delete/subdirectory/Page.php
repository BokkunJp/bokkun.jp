<?php

/**
 * GetPage
 *
 * ページ番号を取得する。
 *
 * @param void
 *
 * @return integer|bool
 */
function getPage(): int|bool
{
    $page = Private\Important\Setting::getQuery('page');
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
function getCountPerPage(): int
{
    $session = new Private\Important\Session();
    $post = Private\Important\Setting::getPost('image-value');
    if (isset($post) && is_numeric($post)) {
        $pager= (int) $post;
        // 上限設定
        if ($pager > (PAGER * MAX_VIEW)) {
            $pager = PAGER * MAX_VIEW;
        }
        $session->write('image-view', $pager);
    } elseif ($session->judge('image-view')) {
        $pager = (int)$session->read('image-view');
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
 *  @return void|string
 */
function viewPager(int $max, bool $ajaxFlg = false)
{
    $htmlVal = '';
    $nowPage = getPage();
    $pager = getCountPerPage();
    $minPage = MIN_PAGE_COUNT;
    $maxPage = (int)ceil(bcdiv($max, $pager, COUNT_RECURSIVE));       // 最大ページ(画像数をページ数で割って丸める。精度の問題から除算にはBCMathライブラリのbcdivを使用)

    $pageHtml = new \Private\Tag\CustomTagCreate();

    for ($_index = MIN_PAGE_COUNT, $_vindex = MIN_PAGE_COUNT; $_index <= $max; $_index += $pager, $_vindex++) {
        $pageValid = validateLoop($_vindex, $nowPage, $minPage, $maxPage);
        if ($pageValid === false) {
            $pageHtml->setTag('span', $_vindex . ' ', 'pager', true);
        } elseif ($pageValid === true) {
            $pageHtml->setHref("./?page={$_vindex}", $_vindex, 'pager', false, '_self');
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
                $htmlVal .= $pageHtml->execTag();
            } else {
                $pageHtml->execTag(true);
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
    $htmlVal .= setInputForm($minPage, $maxPage, $ajaxFlg);

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
 * @return null|string|bool
 */
function validateLoop(int $currentPage, int $nowPage, $minPage, int $maxPage): null|string|bool
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
 * ページ移動フォームの生成

 * @param integer $minPage
 * @param integer $maxPage
 * @param boolean $ajaxFlg
 * @return string|bool
 */
function setInputForm(int $minPage, int $maxPage, bool $ajaxFlg = false): string|bool
{
    $htmlVal = "<span class='image-page-input'><input type='number' class='update_page' name='update_page' id='update_page' min=$minPage max=$maxPage />ページへ<button name='move'>移動</button></span>";
    if ($ajaxFlg) {
        return $htmlVal;
    } else {
        print_r($htmlVal);
        return true;
    }
}
