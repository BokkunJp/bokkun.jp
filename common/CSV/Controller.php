<?php
IncludeDirctories();

use Common\CSV;
use PublicSetting\Session;

function Main($inputFlg=false)
{
    $tokenValid = CheckToken();

    if ($tokenValid === false) {
        echo "<div class='warning'>不正な遷移です。</div>";
        return false;
    }

    $csv = new CSV();

    if ($inputFlg === true) {
        $csv->SetHeader(['x', 'y', 'z']);

        // ファイル名を設定
        $valid = $csv->InputName();
        if ($valid === false) {
            echo "<div class='warning'>ファイル名を入力してください。</div>";
        }

        // CSVファイルを取得(編集の場合)
        $valid = $csv->ReadData();
        if ($valid === false) {
            echo "<div class='warning'>列番号の指定が不正です。</div>";
        }

        // 入力値を設定
        $valid = $csv->InputData();
        // CSVファイルを書き込み
        if ($valid !== false) {
            $csv->SetCSV();
        }
    } else {
        // ファイル名を設定
        $valid = $csv->InputName();
        if ($valid === false) {
            echo "<div class='warning'>ファイル名を入力してください。</div>";
        }

        // 出力用データ取得
        $header = $csv->OutData('header');
        $row = $csv->OutData('row');
        $result = $csv->OutData();
        if ($result === false) {
            echo "<div class='warning'>ご指定のファイルは存在しません。</div>";
        }

        $header = MoldData($header);
        $body = "";
        foreach ($row as $_r) {
            $body .= MoldData($_r). nl2br("\n");
        }
        $session = new Session();
        $session->WriteArray('csv', 'header', $header);
        $session->WriteArray('csv', 'row', $body);
    }
}
