<?php
class Logic1 {
  private $_mode;
  private $_param;
  public function init($mode, $param) {
     $this->_mode  = $mode;
     $this->_param = $param;
  }
  public function run() {
    echo __CLASS__ . ' が ' .
         htmlspecialchars($this->_param, ENT_QUOTES) . ' を ' .
         htmlspecialchars($this->_mode , ENT_QUOTES) . 'します';
  }
}
?>