
<?php
/**
 *    対象ディレクトリ内のファイルを一括で読み込む
 *    (対象ディレクトリ内にはファイルのみがある前提)
 *
 * @param string $pwd                   ディレクトリまでのパス
 * @param string $extension             拡張子
 * @param boolean $ret                  結果格納用
 * @param array $classLoad              クラス読み込み用配列
 *
 * @return null|string|array
 */
function IncludeFilesForImage($pwd, $extension = 'php', $ret = false, array $classLoad=[])
{
    // ディレクトリと拡張子の存在チェック
    if (!file_exists($pwd) || is_null($extension)) {
        return null;
    }

    // クラスを読み込む場合は、spl_auto_registerを使う
    if (!empty($classLoad)) {
        return spl_autoload_register(function () use ($pwd, $classLoad) {
            while ($name = current($classLoad)) {
                $logoutPath = new \Path($pwd);
                $logoutPath->setPathEnd();
                $logoutPath->add("{$name}.php");
                require_once $logoutPath->get();
                next($classLoad);
            }
        });
    }

    $dirList = scandir($pwd);           // ファイルリスト取得
    $extension = '.' . $extension;       // 検索用

    $retList = [];
    foreach ($dirList as $_dirList) {
        // 指定した拡張子のファイルのみ許可
        if (strpos($_dirList, $extension) != false) {
            if ($ret === true) {
                $retList[] = $_dirList;
            } else {
                require_once $pwd . $_dirList;
            }
        }
    }

    // 出力ありの場合は、ファイルリストを出力して終了
    if ($ret === true) {
        if (empty($retList)) {
            $retList = [];
        }
        return $retList;
    }
}
