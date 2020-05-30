<?php
header("Content-Type: application/json; charset=UTF-8");

/* @memo require_onceは使えない？ */
// require_once dirname(__DIR__, 2). "/common/require.php";
// use PrivateSetting\Setting;

// $set = new Setting();
// $set->GetPost('test');

if (isset($_POST['test'])) {
    $data = 'success';
} else {
    $data = 'failure';
}
$json = json_encode($data);

echo $json;