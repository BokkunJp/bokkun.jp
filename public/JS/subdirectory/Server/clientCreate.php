<?php
class sample {
  public $test;
  private $ptest;
}
require_once dirname(dirname(dirname(__DIR__))). '/common/Setting.php';
$base = new PublicSetting\Setting();
var_dump($base->GetPost());
