<?php
require_once('interface.php');
class BaseClass implements BaseInterface {
  public function Say($value=null) {
    if (empty($value)) {
      $value=BaseInterface::VALUE;
    }
    echo $value. nl2br("\n");
  }

  public function Calc($x, $y) {
    return $x + $y;
  }
}

class OtherClass extends BaseClass {
    public function Func(BaseClass $baseInterface) {
  }
  public function Say($value=null) {
    parent::Say($value);
    if (empty($value)) {
      $value=BaseInterface::VALUE. "2";
    }
    echo $value. nl2br("\n");
  }

  public function Calc($x, $y) {
    return $x + $y;
  }
}

function MyFunc(...$values) {
  foreach ($values as $_value) {
    echo $_value. nl2br("\n");
  }
}
 ?>
