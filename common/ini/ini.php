<?php

/**
 * GetIni
 *
 * allを指定した場合はすべての値を一次配列で返し、それ以外の場合はパラメータで指定した要素を切り出した配列または値を返す。
 * (正しくない値の場合はfalseを返す。未入力の場合は全データを返す。)
 *
 * @param ...$parameter
 * @return mixed
 */
function GetIni(...$parameter): mixed
{
    $iniFiles = scandir(__DIR__);
    $ini = $iniAll =  [];

    foreach ($iniFiles as $_val) {
        if (!preg_match('/^(.*)\.ini$/', $_val)) {
            continue;
        }
        $ini[explode('.', $_val)[0]] = parse_ini_file($_val);

        foreach (parse_ini_file($_val) as $__key => $__val) {
            $iniAll[$__key] = $__val;
        }
    }

    $ret = false;
    if (empty($parameter) || (empty($ini[$parameter[0]]) && ($parameter[0] !== 'all'))) {
        $ret = $ini;
    } elseif ($parameter[0] === 'all') {
        $ret = $iniAll;
    } elseif (isset($ini[$parameter[0]]) && isset($parameter[1])) {
        if (isset($ini[$parameter[0]][$parameter[1]])) {
            $ret = $ini[$parameter[0]][$parameter[1]];
        }
    } else {
        if (isset($ini[$parameter[0]])) {
            $ret = $ini[$parameter[0]];
        }
    }

    return $ret;
}
