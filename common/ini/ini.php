<?php
/**
 * getIni
 *
 * allを指定した場合はすべての値を一次配列で返し、それ以外の場合はパラメータで指定した要素を切り出した配列または値を返す。
 * (正しくない値の場合はfalseを返す。未入力の場合は全データを返す。)
 *
 * @param ...$parameter
 * 
 * @return mixed
 */
function getIni(...$parameter): mixed
{
    if (searchData('initDirectory', $parameter)) {
        $dir = new Path($parameter['initDirectory']);
    } else {
        $dir = new Path(__DIR__);
    }

    if (searchData('addDirectory', $parameter)) {
        $dir->add($parameter['addDirectory']);
    }

    $iniFiles = scandir($dir->get());
    $ini = [];

    foreach ($iniFiles as $_val) {
        if (!preg_match('/^(.*)\.ini$/', $_val)) {
            continue;
        }
        $ini[explode('.', $_val)[0]] = parse_ini_file($_val);
    }

    // パラメータの指定が不正の場合はいずれもfalse (パラメータ1がallの場合は除外)
    $result = $ini;
    if (!empty($parameter)) {
        foreach ($parameter as $parameterKey => $parameterValue) {
            if (is_array($parameterValue) || $result === false) {
                continue;
            }
            $parameterResult = searchData($parameterValue, $result);
            if ($parameterResult) {
                if (isset($result[$parameterValue])) {
                    $result = $result[$parameterValue];
                } else {
                    $result = $ini[$parameterValue];
                }
            } elseif ($parameterKey === 0 && $parameterValue === 'all') {
                // パラメータ1がallで指定あり
                $result = $ini;
            } else {
                $result = false;
            }
        }
    }

    return $result;
}

/**
 * setIni
 *
 * 配列データの内容をiniファイルに書き込み。
 *
 * @param string $iniName
 * @param array $contents
 * @param string|null $initDirPath
 * 
 * @return boolean
 */
function setIni(string $iniName, array $contents, ?string $initDirPath = null): bool
{
    if (!empty($initDirPath)) {
        $dir = new Path($initDirPath);
    } else {
        $dir = new Path(__DIR__);
    }

    $dir->add($iniName);

    $iniData = '';
    foreach ($contents as $key => $value) {
        $iniData = $key. '='. $value;
    }

    $result = file_put_contents($dir->get(), $iniData);

    if ($result !== false) {
        $result = true;
    }

    return $result;
}
