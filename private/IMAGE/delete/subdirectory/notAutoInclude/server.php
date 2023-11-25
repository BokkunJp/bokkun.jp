<?php

require_once __DIR__ . '/component/require.php';
require_once dirname(__DIR__) . '/File.php';

// ページ数取得
$page = Private\Important\Setting::getQuery('page');
$str = 'private/image/delete/';
$str .= !empty($page) ? "?page={$page}" : "";

// セッション開始
$session = new Private\Important\Session();
$mode = Private\Important\Setting::getQuery('mode');

if (!empty($mode)) {
    if ($mode === 'edit') {
        // view-tokenチェック
        $viewToken = new Private\Important\Token('delete-view-token', $session);
        if ($viewToken->check() === false) {
            $session->write('delete-page-notice', '不正な遷移です。もう一度操作してください。', 'Delete');
            $url = new Private\Important\Setting();
            header('Location:' . $url->getUrl('root', $str));
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
                    $session->write('delete-page-success', $noticeWord, 'Delete');
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
                    $session->write('delete-page-notice', $noticeWord, 'Delete');
                }
            } else {
                // 削除対象が選択されていない場合
                $session->write('delete-page-notice', NOT_FOUND_PERMANENT_DLETE_OR_RESTORE_IMAGE, 'Delete');
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

            $result = restoreImages($restoreImgList);

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
                $session->write('delete-page-notice', $noticeWord);
                // チェックがある場合は、その状態をセッションへ保持
                if (!empty($restoreImgList)) {
                    $session->write('delete-checkImage', array_flip($restoreImgList));
                }
            }
            if (!empty($result['success']['count'])) {
                $session->write('delete-page-success', $result['success']['count']. NUMBER_OF_FILE . SUCCESS_RESTORE_IMAGE);
            }
        } else {
            // 削除以外の場合(不正値)
            $session->write('delete-page-notice', '不正な遷移です。もう一度操作してください。', 'Delete');
            $url = new Private\Important\Setting();
            header('Location:' . $url->getUrl('root', $str));
            exit;
        }
    } elseif ($mode === 'view') {
        // 現在選択している画像タイプを取得
        $imagePageName = getImagePageName();

        // 対象の画像名を取得
        $imageFile = $mode = Private\Important\Setting::getQuery('image');

        // 画像ファイルのパスを取得（GETパラメータから）
        $imgPath = new \Path(PUBLIC_IMAGE_DIR);
        $imgPath->add($imagePageName);
        $imgPath->add('_oldImage');
        $imgPath->setPathEnd();
        $imgPath->add($imageFile);

        if (is_file($imgPath->get())) {
            // 画像のMIMEタイプを取得
            $mimeType = mime_content_type($imgPath->get());
                
            // 画像のContent-Typeを設定
            header('Content-Type: ' . $mimeType);

            // バッファクリア
            ob_clean();

            // 画像ファイルの内容を読み込んで出力
            readfile($imgPath->get());
        }
    }
}

if(isset($posts['delete'])) {
    @session_regenerate_id();
    $session->write('delete-token', sha1(session_id()));
    // $session->finaryDestroy();
}

if ($mode === 'edit') {
    $url = new Private\Important\Setting();
    header('Location:' . $url->getUrl('root', $str));
}