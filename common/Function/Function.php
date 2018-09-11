<?php
function ListAdd($list1, $list2, $extension=null) {
  if (!is_array($list1) || !is_array($list2) || empty($extension)) {
    return false;
  }

  $pullList = array();
  foreach ($list2 as $index => $_dir) {
      if (strpos($_dir, $extension) !== false) {
        $pullList[] = $_dir;
      }
  }

  return array_merge($list1, $pullList);
}
