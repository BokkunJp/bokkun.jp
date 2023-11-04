<?php

function addList(array $list1, array $list2 = [], ?string $extension = null, int $headFlg = -1):array|false
{
    if (is_null($extension)) {
        return false;
    }

    $pullList = array();

    // .の時は特別な判定
    if ($extension == '.') {
        $extension = "\.";
    }

    foreach ($list2 as $index => $_dir) {
        switch ($headFlg) {
        case 0:
        $judge = !preg_match("/^(?!$extension).*$/", $_dir);
          break;
        case 1:
        $judge = preg_match("/^$extension.*$/", $_dir);
          break;
        case 2:
        $judge = strpos($_dir, '.');
          break;
        default:
          $judge = true;
      }

        if ($judge) {
            $pullList[] = $_dir;
        }
    }

    return array_merge($list1, $pullList);
}
