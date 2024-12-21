<?php
// vendor読み込み
setVendor();

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
        $localPath = getcwd();                      // 現在のファイルパスを保管しておく
        chdir($pwd);                      // カレントディレクトリを指定のものに変更
    }

    $dirList = scandir($pwd);              // ファイルリスト取得
    if (is_array($dirList)) {
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
}

/**
 * includeFiles
 *     対象ディレクトリ内のファイルを一括で読み込む
 *     (対象ディレクトリ内にはファイルのみがある前提)
 *
 * @param string  $pwd:ディレクトリまでのパス
 * @param string  [$extension:拡張子]
 * @param boolean [$ret:出力フラグ]
 * @param string [$classLoad:クラスが定義されたファイル名の配列]
 *
 * @return null|bool|array
 */
function includeFiles($pwd, $extension = 'php', $ret = false, array $classLoad = []): null|bool|array
{
    // ディレクトリと拡張子の存在チェック
    if (!file_exists($pwd) || is_null($extension)) {
        return null;
    }

    // クラスを読み込む場合は、spl_auto_registerを使う
    if (!empty($classLoad)) {
        // print_r("<script>alert('クラスをロードしました。');</script>");
        return spl_autoload_register(function () use ($pwd, $classLoad) {
            while (($name = current($classLoad)) !== false) {
                $pwdPath = new \Path($pwd);
                $pwdPath->setPathEnd();
                $pwdPath->add("{$name}.php");
                if (!is_file($pwdPath->get())) {
                    user_error("指定されたファイルが存在しません。");
                }
                require_once $pwdPath->get();
                next($classLoad);
            }
        });
    }


    $dirList = scandir($pwd);           // ファイルリスト取得
    ksort($dirList);                      // 検索順位を昇順に変更
    $extension = '.' . $extension;               // 検索用

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

/**
 * 対象ディレクトリ内のJSファイルを一括で読み込む
 *
 * @param string $pwd                   ディレクトリまでのパス(JSファイルが所定の場所に置いてあることを前提とする)
 * @param string $extension             拡張子
 * @param boolean $resultJudge          結果格納用
 * @param array $classLoad              クラス読み込み用配列
 *
 * @return void
 */
function includeJsFiles($pwd, $extension = 'js', $resultJudge = true, $classLoad = false): void
{
    $src = new OriginTag();
    $base = new Private\Important\Setting();
    $privateJsDir = new \Path(PRIVATE_DIR_LIST['js']);
    $privateJsDir->add($pwd);
    $jsFiles = includeFiles($privateJsDir->get(), $extension, $resultJudge, $classLoad);
    if (is_array($jsFiles)) {
        $jsUrl = new \Path($base->getUrl('js'), '/');
        $jsUrl->add($pwd);
        foreach ($jsFiles as $_jsFile) {
            $jsFilePath = new \Path($jsUrl->get(), '/');
            $jsFilePath->setPathEnd();
            $jsFilePath->add($_jsFile);
            $src->readJs($jsFilePath->get());
            $src->execTag(true);
        }
    }
}
