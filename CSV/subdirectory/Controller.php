<?php

// IncludeDirctories();

function main($inputFlg=false)
{
    $session = new \Public\Important\Session();
    $csvToken = new \Public\Important\Token('csv-token', $session);

    if ($csvToken->check() === false) {
        echo "<div class='warning'>不正な遷移です。</div>";
        return false;
    }

    $csv = new CSV1();

    if ($inputFlg === true) {
        $csv->setHeader(['x', 'y', 'z']);

        // ファイル名を設定
        $valid = $csv->inputName();
        if ($valid === false) {
            echo "<div class='warning'>ファイル名を入力してください。</div>";
            return $valid;
        }

        // CSVファイルを取得(編集の場合)
        $valid = $csv->readData();
        if ($valid === false) {
            echo "<div class='warning'>列番号の指定が不正です。</div>";
        }

        // 入力値を設定
        $valid = $csv->inputData();
        // CSVファイルを書き込み
        if ($valid !== false) {
            $csv->setCsv();
        }
    } else {
        // ファイル名を設定
        $valid = $csv->inputName();
        if ($valid === false) {
            echo "<div class='warning'>ファイル名を入力してください。</div>";
            return $valid;
        }

        // 出力用データ取得
        $header = $csv->outData('header');
        $row = $csv->outData('row');
        $result = $csv->outData();
        if ($result === false) {
            echo "<div class='warning'>ご指定のファイルは存在しません。</div>";
            return $result;
        }

        $header = moldData($header);
        $body = "";
        foreach ($row as $_r) {
            $body .= moldData($_r). nl2br("\n");
        }
        $session = new Public\Important\Session();
        $session->writeArray('csv', 'header', $header);
        $session->writeArray('csv', 'row', $body);
    }
}
