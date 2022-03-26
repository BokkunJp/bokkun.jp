<!DOCTYPE html>
<?php
// 初期設定を記述
$homepageTitle = htmlspecialchars(basename(__DIR__));
http_response_code(507);
require_once dirname(__DIR__). '/common/Layout/layout.php';
