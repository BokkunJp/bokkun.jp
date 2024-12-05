<?php

// includeDirctories();

function main($inputFlg=false)
{
    $session = new \Public\Important\Session('product01-csv-token');
    $csvToken = new \Public\Important\Token('product01-csv-token', $session);

    if ($csvToken->check() === false) {
        echo "<div class='warning'>不正な遷移です。</div>";
        return false;
    }

    $csv = new productCSV();

    if ($inputFlg === true) {
        $csv->setHeader(['x', 'y', 'z']);

        // ファイル名を設定
        $valid = $csv->inputName();
        if ($valid === false) {
            echo "<div class='warning'>ファイル名を入力してください。</div>";
            return false;
        }

        // CSVファイルを取得(編集の場合)
        $valid = $csv->readData();
        if ($valid === false) {
            echo "<div class='warning'>列番号の指定が不正です。</div>";
            return false;
        }

        // 入力値を設定
        $valid = $csv->inputData();
        if ($valid === false) {
            return false;
        }

        // CSVファイルを書き込み
        if ($valid === true) {
            $csv->setCsv();
        }
    } else {
        // ファイル名を設定
        $valid = $csv->inputName();
        if ($valid === false) {
            echo "<div class='warning'>ファイル名を入力してください。</div>";
            return false;
        }

        // 出力用データ取得
        $header = $csv->outData('header');
        $row = $csv->outData('row');
        $result = $csv->outData();
        if ($result === false) {
            echo "<div class='warning'>ご指定のファイルは存在しません。</div>";
            return false;
        }

        $header = $csv->moldData($header);
        $body = "";
        foreach ($row as $_r) {
            $body .= $csv->moldData($_r). nl2br("\n");
        }
        $session = new Public\Important\Session('csv');
        $session->writeArray('csv', 'header', $header);
        $session->writeArray('csv', 'row', $body);
    }
}

// CSVオブジェクトを作成
// CSVオブジェクトにデータを挿入
// ファイルに書き出す ←ｲﾏｺｺ
