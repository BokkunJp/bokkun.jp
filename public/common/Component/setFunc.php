<?php
// 関数フォルダ内にあるファイルをインポート
namespace setFunc;

require_once('UA.php');
// 指定したクラスの関数を実行する
function Addition($class_name, $instance_name)
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
function GetData($class_name, $data)
{
    Addition($class_name, $data);
}

// 関数にデータをセットする
function SetData($func_name, $data)
{
}

function is_true($param)
{
    if ($param === true) {
        return true;
    }
    return false;
}
