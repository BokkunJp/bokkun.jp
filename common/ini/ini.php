<?php

/**
 * getIni
 *
 * allを指定した場合はすべての値を一次配列で返し、それ以外の場合はパラメータで指定した要素を切り出した配列または値を返す。
 * (正しくない値の場合はfalseを返す。未入力の場合は全データを返す。)
 *
 * @param ...$parameter
 * @return mixed
 */
function getIni(...$parameter): mixed
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

    // パラメータの指定が不正の場合はいずれもfalse (パラメータ1がallの場合は除外)
    $ret = false;
    if (empty($parameter)) {
        // パラーメータの指定無
        $ret = $ini;
    } elseif (isset($ini[$parameter[0]]) && isset($parameter[1]) && isset($ini[$parameter[0]][$parameter[1]])) {
        // パラメータ1, パラメータ2共に正しいパラメータで指定あり
        $ret = $ini[$parameter[0]][$parameter[1]];
    } elseif (isset($ini[$parameter[0]]) && !isset($parameter[1])) {
        // パラメータ1のみ正しいパラメータで指定あり
        $ret = $ini[$parameter[0]];
    } elseif ($parameter[0] === 'all') {
        // パラメータ1がallで指定あり
        $ret = $iniAll;
    }

    return $ret;
}
