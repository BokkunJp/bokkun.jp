
<?php
/**
 * 対象ディレクトリ内のJSファイルを一括で読み込み、HTMLのscriptタグとして出力する
 *
 * @param string $pwd                   ディレクトリまでのパス(JSファイルが所定の場所に置いてあることを前提とする)
 * @param string $extension             拡張子
 * @param boolean $ret                  結果格納用
 * @param array $classLoad              クラス読み込み用配列
 *
 * @return null|string|array
 */
function includeFiles($pwd, $extension = 'php', $ret = false, array $classLoad=[]): null|string|array
{
    // ディレクトリと拡張子の存在チェック
    if (!file_exists($pwd) || is_null($extension)) {
        return null;
    }

    // クラスを読み込む場合は、spl_auto_registerを使う
    if (!empty($classLoad)) {
        return spl_autoload_register(function () use ($pwd, $classLoad) {
            while ($name = current($classLoad)) {
                $path = new Path($pwd);
                $path->setPathEnd();
                require_once $path->add("{$name}.php", false);
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
    }

    return $retList;
}
