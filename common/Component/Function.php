<?php
function AddList($list1, $list2, $extension=null, $headFlg=-1)
{
    if (!is_array($list2) || empty($extension)) {
        return false;
    }

    if (!is_array($list1)) {
        $list1 = array();
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
