<!DOCTYPE html>
<?php
if (!isset($_SESSION)) {
    session_start();
}
// 初期設定を記述
$homepageTitle = htmlspecialchars(basename(__DIR__));
// アクセスしたページが既存ページの大文字・小文字の違いであれば、既存ページに遷移
$pageList = scandir(dirname(__DIR__, 3));
$topURL = basename($_SERVER['REQUEST_URI']);
if ($topURL !== 'private' && !preg_match("/^\_.*$/", $topURL)) {
    foreach ($pageList as $_page) {
        if (!preg_match("/^\..*$/", $_page) && is_dir(dirname(__DIR__, 3). DIRECTORY_SEPARATOR .$_page)) {
            if (stripos($_page, $topURL) === 0) {
                if (is_dir(dirname(__DIR__, 3). DIRECTORY_SEPARATOR. $_page)) {
                    header('Location:https://'. $_SERVER['SERVER_NAME']. '/'. $_page);
                    exit;
                }
            }
        }
    }
}
http_response_code(404);
require_once dirname(__DIR__). '/common/Layout/layout.php';
