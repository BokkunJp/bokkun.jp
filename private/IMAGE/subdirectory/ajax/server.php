<?php
header("Content-Type: application/json; charset=UTF-8");
define("DS", DIRECTORY_SEPARATOR);

require_once dirname(__DIR__, 3) . DS . "common" . DS . "ajax-require.php";
require_once AddPath(getcwd(), 'include.php', false);
IncludeFiles(AddPath(dirname(__DIR__), ''));

// tokenチェック
$checkToken = CheckToken('select-token');

// 不正tokenの場合は、エラーを出力して処理を中断。
if ($checkToken === false) {
    $data = ['src' => true];
    $json = json_encode($data);
    echo $json;
    exit;
}

// Post値の検証
$postValid = ValidateData(PUBLIC_IMAGE_DIR, $post['type']);

// 不正なPostが入った場合は、強制的にデフォルトのページ(IMAGE)を参照するようにする
if ($postValid === false) {
    // デフォルトのページ(IMAGE)もない場合はエラーを出力して処理を中断。
    $defaultValid = ValidateData(PUBLIC_IMAGE_DIR, DEFAULT_IMAGE);
    if ($defaultValid === false) {
        $data = ['error' => '', 'src-view' => '必要なディレクトリがありません。'];
        $json = json_encode($data);
        echo $json;
        exit;
    }
    $post['type'] =  DEFAULT_IMAGE;
}

// 表示内容をもとに、表示する対象の画面を決定
if (!empty($post['type'])) {
    $session->Write('image-view-directory', $post['type']);
} elseif (empty($session->Judge('image-view-directory'))) {
    $session->Write('image-view-directory', DEFAULT_IMAGE);
}

// 画像群を取得して、フロント処理側に返却
$img = ReadImage(ajaxFlg:true);
$json = json_encode($img);
echo $json;
