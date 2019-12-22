<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once __DIR__ . '/require.php';
$title = "画像の追加・削除";
$img = "crown-vector.jpg";
