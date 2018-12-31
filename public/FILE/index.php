<?php
if (!isset($_SESSION)) {
    session_start();
}
// 初期設定を記述
$homepageTitle = htmlspecialchars(basename(__DIR__));
require_once dirname(__DIR__). '/common/Layout/layout.php';