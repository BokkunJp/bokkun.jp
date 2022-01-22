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
            if (!DeleteImage()) {
                $session->Write('notice', 'ファイルの削除に失敗しました。', 'Delete');
            }
        } else {
            $session->Write('notice', NOT_FOUND_DLETE_IMAGE, 'Delete');
        }
        if (!$session->Judge('notice')) {
            $session->Write('success', ($count - COUNT_START) . SUCCESS_DELTE_IMAGE, 'Delete');
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
                $noticeWord .= "・". $result['illegal-value']['count']. FILE_ERR_CONST . ILLEGAL_IMAGE_NAME;
            } else if (!empty($result['error']['count'])) {
                // その他コピー処理エラー
                $noticeWord .= "・". $result['error']['count']. FAIL_COPYING_IMAGE;
            }
            $session->Write('notice', $noticeWord);
        }
            // チェックがある場合は、その状態をセッションへ保持
            if (!empty($copyImgList)) {
                $session->Write('checkImage', array_flip($copyImgList));
            }
        }
        if (!empty($result['success']['count'])) {
            define('FILE_SUCCESS_CONST', "{$result['success']['count']}枚のファイル");
            $session->Write('success', FILE_SUCCESS_CONST . SUCCESS_COPY_IMAGE);
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

    /** @var array ファイルアップロード結果
    */
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
                define('FILE_FAIL_CONST', "{$failCount}枚のファイル");
                $noticeWord .= FILE_FAIL_CONST . FAIL_UPLOAD_IMAGE;

                if (!empty($noticeWord)) {
                    $noticeWord .= nl2br("\n");
                }

                if (!empty($result['-1']['count'])) {
                define('FILE_ERR_CONST', "{$result['-1']['count']}枚のファイル");
                $noticeWord .= "・". FILE_ERR_CONST . NOT_MATCH_IMAGE;
            }

            if (!empty($result['size']['count'])) {
                define('FILE_SIZE_CONST', "{$result['size']['count']}枚のファイル");
                $noticeWord .= "・". FILE_SIZE_CONST . EMPTY_IMAGE_SIZE;
            }

            if (!empty($result['illegal']['count'])) {
                define('FILE_ILLEGAL_CONST', "{$result['illegal']['count']}枚のファイル");
                $noticeWord .= "・". FILE_ILLEGAL_CONST . ILLEGAL_UPLOAD_IMAGE;
            }

            $session->Write('notice', $noticeWord);

        }
        if (!empty($result['success']['count'])) {
            define('FILE_SUCCESS_CONST', "{$result['success']['count']}枚のファイル");
            $session->Write('success', FILE_SUCCESS_CONST . SUCCESS_UPLOAD_IMAGE);
        }
    }
}
@session_regenerate_id();
$session->Write('token', sha1(session_id()));
// $session->FinaryDestroy();
$url = new PrivateSetting\Setting();
header('Location:' . $url->GetUrl($str));
?>