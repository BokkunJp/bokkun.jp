<?php
require_once __DIR__ . '/component/require.php';
require_once dirname(__DIR__) . '/File.php';
$files = PrivateSetting\Setting::GetFiles();

// ページ数取得
$page = PrivateSetting\Setting::GetQuery('page');
$str = 'private/IMAGE';
$str .= !empty($page) ? "?page={$page}" : "";

// セッション開始
if (!isset($session)) {
    $session = new PrivateSetting\Session();
}

$mode = PrivateSetting\Setting::GetQuery('mode');

if (!empty($mode) && $mode === 'edit') {
    // view-tokenチェック
    $checkToken = CheckToken('view-token');
    // 不正tokenの場合は、エラーを出力して処理を中断。
    if ($checkToken === false) {
        $session->Write('notice', '不正な遷移です。もう一度操作してください。', 'Delete');
        $url = new PrivateSetting\Setting();
        header('Location:' . $url->GetUrl($str));
        exit;
    }

    $count = 0;
    $posts = PrivateSetting\Setting::getPosts();

    if (isset($posts['delete'])) {
        // 削除の場合
        foreach (PrivateSetting\Setting::getPosts() as $post_key => $post_value) {
            $count++;
        }
        if ($count > COUNT_START) {
            $deleteResult = DeleteImage();

            $noticeWord = '';
            // 削除失敗した画像について
            if (isset($deleteResult['error'])) {
                $noticeWord = count($deleteResult['error']). FAIL_DELETE_IMAGE;
                $errorResult = $deleteResult['error'];
                $noticeWord .= nl2br("\n");
                foreach ($errorResult as $_key => $_result) {
                    switch ($_result) {
                        case false:
                            $noticeWord .= "・".FAIL_REASON_SYSTEM;
                            break;
                        case ILLEGAL_RESULT:
                            $noticeWord .= "・".FAIL_REASON_ILLEGAL;
                            break;
                        default:
                            break;
                    }
                    $noticeWord .= $_key. FAIL_DELETE_IMAGE_DETAIL;
                    $noticeWord .= nl2br("\n");
                }
                $session->Write('notice', $noticeWord, 'Delete');
            }
            // 削除成功した画像について
            if (isset($deleteResult['success'])) {
                $noticeWord .= count($deleteResult['success']). SUCCESS_DELETE_IMAGE;
                $successResult = $deleteResult['success'];
                $noticeWord .= nl2br("\n");
                foreach ($successResult as $_key => $_result) {
                    $noticeWord .= "・".$_key. SUCCESS_DELETE_IMAGE_DETAIL;
                }
                $session->Write('success', $noticeWord, 'Delete');
            }
        } else {
            $session->Write('notice', NOT_FOUND_DLETE_IMAGE, 'Delete');
        }
    } else if (isset($posts['copy'])) {
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
                $noticeWord .= nl2br("\n"). "・". NOT_FOUND_COPY_DIRECTORY;
            } else if (!empty($result['no-select'])) {
                // 選択画像がなし
                $noticeWord .= nl2br("\n"). "・". NOT_SELECT_IMAGE;
            } else {
            $noticeWord .= nl2br("\n");
            if(!empty($result['illegal-value']['count'])) {
                // 不正なファイル名が入力された
                $noticeWord .= nl2br("\n");
                $noticeWord .= "・". $result['illegal-value']['count']. NUMBER_OF_IMAGE . ILLEGAL_IMAGE_NAME;
            } else if (!empty($result['error']['count'])) {
                // その他コピー処理エラー
                $noticeWord .= "・". $result['error']['count']. FAIL_COPYING_IMAGE;
            }
        }
            $session->Write('notice', $noticeWord);
            // チェックがある場合は、その状態をセッションへ保持
            if (!empty($copyImgList)) {
                $session->Write('checkImage', array_flip($copyImgList));
            }
        }
        if (!empty($result['success']['count'])) {
            $session->Write('success', $result['success']['count']. NUMBER_OF_IMAGE . SUCCESS_COPY_IMAGE);
        }
    } else {
        // 削除・複製以外の場合(不正値)
        $session->Write('notice', '不正な遷移です。もう一度操作してください。', 'Delete');
        $url = new PrivateSetting\Setting();
        header('Location:' . $url->GetUrl($str));
        exit;
    }
} else {
    // upload-tokenチェック
    $checkToken = CheckToken('upload-token');
    // 不正tokenの場合は、エラーを出力して処理を中断。
    if ($checkToken === false) {
        $session->Write('notice', '不正な遷移です。もう一度操作してください。', 'Delete');
        $url = new PrivateSetting\Setting();
        header('Location:' . $url->GetUrl($str));
        exit;
    }

    $result = ImportImage($files);

    // ファイルがアップロードされなかった場合
    if (empty($result['success'])) {
        if (empty($result)) {
            $session->Write('notice', NOT_SELECT_IMAGE);
        } else if ($result == IMAGE_COUNT_OVER) {
            $session->Write('notice', IMAGE_COUNT_OVER_ERROR);
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
                define('FILE_FAIL_CONST', "{$failCount}枚の画像ファイル");
                $noticeWord .= FILE_FAIL_CONST . FAIL_UPLOAD_IMAGE;

                if (!empty($noticeWord)) {
                    $noticeWord .= nl2br("\n");
                }

                if (!empty($result['-1']['count'])) {
                define('FILE_ERR_CONST', "{}枚の画像ファイル");
                $noticeWord .= "・". $result['-1']['count']. NUMBER_OF_IMAGE. NOT_MATCH_IMAGE;
            }

            if (!empty($result['size']['count'])) {
                $noticeWord .= "・". $result['size']['count']. NUMBER_OF_IMAGE. EMPTY_IMAGE_SIZE;
            }

            if (!empty($result['illegal']['count'])) {
                $noticeWord .= "・". $result['illegal']['count'] . NUMBER_OF_IMAGE. ILLEGAL_UPLOAD_IMAGE;
            }

            $session->Write('notice', $noticeWord);

        }
        if (!empty($result['success']['count'])) {
            $session->Write('success', $result['success']['count']. NUMBER_OF_IMAGE . SUCCESS_UPLOAD_IMAGE);
        }
    }
}
@session_regenerate_id();
$session->Write('token', sha1(session_id()));
// $session->FinaryDestroy();
$url = new PrivateSetting\Setting();
header('Location:' . $url->GetUrl($str));
?>