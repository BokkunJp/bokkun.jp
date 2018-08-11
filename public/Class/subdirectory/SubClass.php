<?php
require_once 'SuperClass.php';
class SubClass extends SuperClass {
  function __construct() {
    $this->setData = new SuperClass();
  }
}
