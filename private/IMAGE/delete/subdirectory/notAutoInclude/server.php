<?php

require_once __DIR__ . '/component/require.php';
require_once dirname(__DIR__) . '/File.php';

// ページ数取得
$page = private\Setting::GetQuery('page');
$str = 'private/IMAGE/delete/';
$str .= !empty($page) ? "?page={$page}" : "";

// セッション開始
$session = new private\Session();
$mode = private\Setting::GetQuery('mode');

if (!empty($mode) && $mode === 'edit') {
    // view-tokenチェック
    $viewToken = new private\Token('delete-view-token', $session);
    if ($viewToken->Check() === false) {
        $session->Write('delete-page-notice', '不正な遷移です。もう一度操作してください。', 'Delete');
        $url = new private\Setting();
        header('Location:' . $url->GetUrl($str));
        exit;
    }

    $posts = private\Setting::getPosts();

    if (isset($posts['delete'])) {
        // 削除の場合
        $deleteImages = $imageNameArray = [];
        $allImage = LoadAllImageFile();

        $judge = ValidateDeleteImage($posts);
        if ($judge === true) {
            foreach ($posts as $_key => $_value) {
                $imageNameArray[$_key] = $_value;
                if (preg_match('/^img_(.*)$/', $_key)) {
                    if (ValidateDeleteImage($_value, $allImage) === true) {
                        $deleteImages[$_key] = $_value;
                    } else {
                        $deleteImages[$_key] = false;
                        $deleteImageName[$_key] = $_value;
                    }
                }
            }

            $deleteResult = DeleteImages($deleteImages);
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
                $session->Write('delete-page-success', $noticeWord, 'Delete');
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
                $session->Write('delete-page-notice', $noticeWord, 'Delete');
            }
        } else {
            // 削除対象が選択されていない場合
            $session->Write('delete-page-notice', NOT_FOUND_PERMANENT_DLETE_OR_RESTORE_IMAGE, 'Delete');
        }
    } elseif (isset($posts['restore'])) {
        // 復元の場合
        // 選択したファイルをリスト化
        $restoreImgList = [];
        foreach ($posts as $_key => $_post) {
            if (preg_match('/^img_(.*)$/', $_key)) {
                $restoreImgList[] = $_post;
            }
        }

        $result = RestoreImages($restoreImgList);

        $failCount = 0;
        foreach ($result as $_key => $_r) {
            if ($_key === "success" || !isset($_r['count']) || $_r['count'] === 0) {
                continue;
            }
            $failCount += $_r['count'];
        }

        if (!empty($failCount)) {
            // ファイルコピー失敗
            $noticeWord = FAIL_RESTORE;

            // 対象ディレクトリがなし
            if (!empty($result['not-page'])) {
                $noticeWord = NOT_FOUND_PERMANENT_DLETE_OR_RESTORE_IMAGE;
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
                    $noticeWord .= "・". $result['error']['count']. NUMBER_OF_FILE .FAIL_RESTORE_IMAGE;
                }
            }
            $session->Write('delete-page-notice', $noticeWord);
            // チェックがある場合は、その状態をセッションへ保持
            if (!empty($restoreImgList)) {
                $session->Write('delete-checkImage', array_flip($restoreImgList));
            }
        }
        if (!empty($result['success']['count'])) {
            $session->Write('delete-page-success', $result['success']['count']. NUMBER_OF_FILE . SUCCESS_RESTORE_IMAGE);
        }

     } else {
        // 削除以外の場合(不正値)
        $session->Write('delete-page-notice', '不正な遷移です。もう一度操作してください。', 'Delete');
        $url = new private\Setting();
        header('Location:' . $url->GetUrl($str));
        exit;
    }
}
@session_regenerate_id();
$session->Write('delete-token', sha1(session_id()));
// $session->FinaryDestroy();
$url = new private\Setting();
header('Location:' . $url->GetUrl($str));