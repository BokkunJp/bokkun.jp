<?php

// 関数フォルダ内にあるファイルをインポート

namespace SetFunc;

require_once('Ua.php');
// 指定したクラスの関数を実行する
function addition($class_name, $instance_name)
{
    $newData = new $class_name();

    // クラスのインスタンスの判定
    if (is_object($newData.$instance_name)) {
        return $newData;
    } else {
        return null;
    }
}

// 関数からデータを取得する
function getData($class_name, $data)
{
    addition($class_name, $data);
}

// 関数にデータをセットする
function setData($func_name, $data)
{
}

function isTrue($param)
{
    if ($param === true) {
        return true;
    }
    return false;
}
