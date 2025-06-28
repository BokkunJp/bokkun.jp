<?php

require_once __DIR__ . '/component/require.php';
require_once dirname(__DIR__) . '/File.php';
$files = Private\Important\Setting::getFiles();

// ページ数取得
$page = htmlspecialchars(Private\Important\Setting::getQuery('page'));
$str = 'private/image';
$str .= !empty($page) ? "?page={$page}" : "";

// セッション開始
$session = new Private\Important\Session('image');

$mode = Private\Important\Setting::getQuery('mode');

if (!empty($mode) && $mode === 'edit') {
    // view-tokenチェック
    $viewToken = new Private\Important\Token('view-token', $session);
    if ($viewToken->check() === false) {
        $session->write('notice', '不正な遷移です。もう一度操作してください。');
        $setting = new Private\Important\Setting();
        header('Location:' . $setting->getUrl('root', $str));
        exit;
    }

    $posts = Private\Important\Setting::getPosts();

    if (isset($posts['delete'])) {
        // 削除の場合
        $deleteImages = $imageNameArray = [];
        $allImage = loadAllImageFile();

        $judge = validateDeleteImage($posts);
        if ($judge === true) {
            foreach ($posts as $_key => $_value) {
                $imageNameArray[$_key] = $_value;
                if (preg_match('/^img_(.*)$/', $_key)) {
                    if (validateDeleteImage($_value, $allImage) === true) {
                        $deleteImages[$_key] = $_value;
                    } else {
                        $deleteImages[$_key] = false;
                        $deleteImageName[$_key] = $_value;
                    }
                }
            }

            $deleteResult = deleteImages($deleteImages);
            $noticeWord = '';

            // 削除成功した画像について
            if (isset($deleteResult['success'])) {
                $noticeWord = count($deleteResult['success']). SUCCESS_DELETE_IMAGE;
                $successResult = $deleteResult['success'];
                $noticeWord .= nl2br("\n");
                foreach ($successResult as $_key => $_result) {
                    $noticeWord .= "・". $imageNameArray[$_key]. SUCCESS_DELETE_IMAGE_DETAIL;
                    $noticeWord .= nl2br("\n");
                }
                $session->write('success', $noticeWord);
            }

            // 削除失敗した画像について
            if (isset($deleteResult['error'])) {
                $noticeWord = count($deleteResult['error']). FAIL_DELETE_IMAGE;
                $errorResult = $deleteResult['error'];
                $noticeWord .= nl2br("\n");
                foreach ($errorResult as $_key => $_result) {
                    $noticeWord .= "・". $imageNameArray[$_key]. FAIL_DELETE_IMAGE_DETAIL;
                    $noticeWord .= nl2br("\n");
                }
                $session->write('notice', $noticeWord);
            }
        } else {
            // 削除対象が選択されていない場合
            $session->write('notice', NOT_FOUND_DLETE_IMAGE);
        }
    } elseif (isset($posts['copy'])) {
        // コピーの場合
        // 選択したファイルをリスト化
        $copyImgList = [];
        foreach ($posts as $_key => $_post) {
            if (preg_match('/^img_(.*)$/', $_key)) {
                $copyImgList[] = $_post;
            }
        }

        $result = CopyImage($copyImgList);

        $failCount = 0;
        foreach ($result as $_key => $_r) {
            if ($_key === "success" || !isset($_r['count']) || $_r['count'] === 0) {
                continue;
            }
            $failCount += $_r['count'];
        }

        if (!empty($failCount)) {
            // ファイルコピー失敗
            $noticeWord = FAIL_COPY_IMAGE;

            // 対象ディレクトリがなし
            if (!empty($result['not-page'])) {
                $noticeWord .= nl2br("\n"). "・". NOT_FOUND_DIRECTORY;
            } elseif (!empty($result['no-select'])) {
                // 選択画像がなし
                $noticeWord .= nl2br("\n"). "・". NOT_SELECT_IMAGE;
            } else {
                $noticeWord .= nl2br("\n");
                if (!empty($result['illegal-value']['count'])) {
                    // 不正なファイル名が入力された
                    $noticeWord .= nl2br("\n");
                    $noticeWord .= "・". $result['illegal-value']['count']. NUMBER_OF_FILE . ILLEGAL_IMAGE_NAME;
                } elseif (!empty($result['error']['count'])) {
                    // その他コピー処理エラー
                    $noticeWord .= "・". $result['error']['count']. FAIL_COPYING_IMAGE;
                }
            }
            $session->write('notice', $noticeWord);
            // チェックがある場合は、その状態をセッションへ保持
            if (!empty($copyImgList)) {
                $session->write('checkImage', array_flip($copyImgList));
            }
        }
        if (!empty($result['success']['count'])) {
            $session->write('success', $result['success']['count']. NUMBER_OF_FILE . SUCCESS_COPY_IMAGE);
        }
    } else {
        // 削除・複製以外の場合(不正値)
        $session->write('notice', '不正な遷移です。もう一度操作してください。');
        $url = new Private\Important\Setting();
        header('Location:' . $url->getUrl('root', $str));
        exit;
    }
} else {
    // upload-tokenチェック
    $uploadToken = new Private\Important\Token('upload-token', $session);
    if ($uploadToken->check() === false) {
        $session->write('notice', '不正な遷移です。もう一度操作してください。');
        $url = new Private\Important\Setting();
        header('Location:' . $url->getUrl('root', $str));
        exit;
    }

    $result = importImage($files);

    // ファイルがアップロードされなかった場合
    if (empty($result['success'])) {
        if (empty($result)) {
            $session->write('notice', NOT_SELECT_IMAGE);
        } elseif ($result == IMAGE_COUNT_OVER) {
            $session->write('notice', IMAGE_COUNT_OVER_ERROR);
        } else {
            // 1枚以上アップロードに成功したファイルがあった場合
            // 一度も発生しなかった結果パターンを除外
            foreach ($result as $_key => $_filter) {
                if (empty($_filter['count'])) {
                    unset($result[$_key]);
                }
            }
        }
    } else {
        $failCount = 0;
        foreach ($result as $_key => $_r) {
            if ($_key === "success" || $_r['count'] === 0) {
                continue;
            }
            $failCount += $_r['count'];
        }

        if (!empty($failCount)) {
            $noticeWord = "";
            $noticeWord .= $failCount . NUMBER_OF_FILE . FAIL_UPLOAD_IMAGE;

            if (!empty($noticeWord)) {
                $noticeWord .= nl2br("\n");
            }

            if (!empty($result['-1']['count'])) {
                $noticeWord .= "・". $result['-1']['count']. NUMBER_OF_FILE. NOT_MATCH_IMAGE;
            }

            if (!empty($result['size']['count'])) {
                $noticeWord .= "・". $result['size']['count']. NUMBER_OF_FILE. EMPTY_IMAGE_SIZE;
            }

            if (!empty($result['illegal']['count'])) {
                $noticeWord .= "・". $result['illegal']['count'] . NUMBER_OF_FILE. ILLEGAL_UPLOAD_IMAGE;
            }

            $session->write('notice', $noticeWord);
        }
        if (!empty($result['success']['count'])) {
            $session->write('success', $result['success']['count']. NUMBER_OF_FILE . SUCCESS_UPLOAD_IMAGE);
        }
    }
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
session_regenerate_id();
$session->write('token', sha1(session_id()));
// $session->delete();
$url = new Private\Important\Setting();
header('Location:' . $url->getUrl('root', $str));
