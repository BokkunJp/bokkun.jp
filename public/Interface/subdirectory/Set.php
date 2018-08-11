<?php
require_once('IncludeInterface.php');

class Set Implements AccessInterface\SetInterface {
  private $value;
  private function Set($val) { $this->value = $val; }
  public function SetNumber($val) {}
  public function SetArray($val) {}
  public function SetString($val) {}

}
