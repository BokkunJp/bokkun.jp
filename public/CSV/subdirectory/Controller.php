<?php
require_once PUBLIC_COMMON_DIR. '/Token.php';
IncludeDirctories();
$token = PublicSetting\Setting::GetPost('token');

Main();

function Main() {
    $csv = new CSV();

//    var_Dump(PublicSetting\Setting::GetPosts());

    $csv->SetHeader(['x', 'y', 'z']);
    $csv->SetData([1, 2, 4]);
    $csv->SetData([2.5, 1.31, 2.47]);
    $csv->SetData([2.55, 1.323, 3.162]);

    $csv->MakeFile('test.csv');

}

// CSVオブジェクトを作成
// CSVオブジェクトにデータを挿入
// ファイルに書き出す ←ｲﾏｺｺ

?>
