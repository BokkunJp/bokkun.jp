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
            DeleteImage();
        } else {
            $session->Write('notice', '削除対象が選択されていないか、画像がありません。', 'Delete');
        }
        if (!$session->Judge('notice')) {
            $session->Write('success', ($count - COUNT_START) . '件の画像の削除に成功しました。', 'Delete');
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
                $noticeWord .= nl2br("\n"). "・". NOT_FAUND_COPY_DIRECTORY;
            }

            // 選択画像がなし
            if (!empty($result['no-select'])) {
                $noticeWord .= nl2br("\n"). "・". NOT_SELECT_IMAGE;
            }

            // コピー処理エラー
            if (!empty($result['error']['count'])) {
                $noticeWord .= nl2br("\n");
                define('FILE_ERR_CONST', "{$result['error']['count']}枚のファイル");
                $noticeWord .= "・". FILE_ERR_CONST . FAIL_COPYING_IMAGE;
            }
            $session->Write('notice', $noticeWord);

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
            $session->Write('notice', FILE_NONE);
        } else if ($result == FILE_COUNT_OVER) {
            $session->Write('notice', FILE_COUNT_OVER_ERROR);
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

                if (!empty($result['-1']['count'])) {
                if (!empty($noticeWord)) {
                    $noticeWord .= nl2br("\n");
                }
                define('FILE_ERR_CONST', "{$result['-1']['count']}枚のファイル");
                $noticeWord .= "・". FILE_ERR_CONST . NOT_MATCH_IMAGE;
            }

            if (!empty($result['size']['count'])) {
                if (!empty($noticeWord)) {
                    $noticeWord .= nl2br("\n");
                }
                define('FILE_SIZE_CONST', "{$result['size']['count']}枚のファイル");
                $noticeWord .= "・". FILE_SIZE_CONST . EMPTY_IMAGE_SIZE;
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