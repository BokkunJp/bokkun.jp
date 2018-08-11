<?php
namespace Time;

function DateValid($date) {
  // ****-**-**を分離
  $dateAry = explode('-', $date);
  if ($dateAry[0] > 2021) {
    return false;
  }
  // 月と日の判定は行わない
  // if ($dateAry[1] > 12 && 1 > $dateAry[1]) {
  //   return false;
  // }
  // if ($dateAry[2] > 31 && 1 > $dateAry[2]) {
  //   return false;
  // }

}

function MyDateTimePeriod($start, $end) {

}
