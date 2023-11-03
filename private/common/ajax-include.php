<?php
/**
 * IncludeDirectories
 *
 * 対象ディレクトリ内のファイルをディレクトリごと一括で読み込む
 *
 * @param string $pwd                   ディレクトリまでのパス
 * @param string $extension             拡張子
 * @param boolean $ret                  結果格納用
 * @param array $classLoad              クラス読み込み用配列
 *
 * @return null|string|array
 */
function includeDirectories($pwd = '', $extension = 'php', $ret = false, array $classLoad=[])
{
    // パスの指定がない場合は、カレントディレクトリ一覧を取得
    if (empty($pwd)) {
        $pwd = getcwd();
    // パスの指定がある場合は、カレントディレクトリを(現在のものを保存したうえで)書き換える
    } elseif ($pwd != getcwd()) {
        $localPath = getcwd();            // 現在のファイルパスを保管しておく
        chdir($pwd);                      // カレントディレクトリを指定のものに変更
    }

    $dirList = scandir($pwd);           // ファイルリスト取得
    foreach ($dirList as $_dirList) {
        if (is_dir($_dirList) && !is_numeric(strpos($_dirList, '.'))) {
            $includeDir = new \Path($pwd);
            $includeDir->add($_dirList);
            includeFiles($includeDir->get(), $extension, $ret, $classLoad);
        }
    }
    if (isset($localPath)) {
        chdir($localPath);                // カレントディレクトリを元のパスに戻す
    }

    // 出力ありの場合は、ディレクトリリストを出力して終了
    if ($ret === true) {
        return $dirList;
    }
}

/**
 *    対象ディレクトリ内のファイルを一括で読み込む
 *    (対象ディレクトリ内にはファイルのみがある前提)
 *
 * @param string $pwd                   ディレクトリまでのパス
 * @param string $extension             拡張子
 * @param boolean $ret                  結果格納用
 * @param array $classLoad              クラス読み込み用配列
 *
 * @return null|array
 */
function includeFiles($pwd, $extension = 'php', $ret = false, array $classLoad=[])
{
    // ディレクトリと拡張子の存在チェック
    if (!file_exists($pwd) || is_null($extension)) {
        return null;
    }

    // クラスを読み込む場合は、spl_auto_registerを使う
    if (!empty($classLoad)) {
        return spl_autoload_register(function () use ($pwd, $classLoad) {
            while ($name = current($classLoad)) {
                $classLoader = new \Path($pwd);
                $classLoader->setPathEnd();
                $classLoader->add("{$name}.php");
                require_once $classLoader->get();
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
            // 出力ありの場合は、ファイルリストを配列に追加
                $retList[] = $_dirList;
            } else {
                require_once $pwd . $_dirList;
            }
        }
    }

    return $retList;
}
