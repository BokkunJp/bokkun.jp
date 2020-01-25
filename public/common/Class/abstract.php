<?php
abstract class AbstractBase {
    abstract protected function getValue();
    abstract protected function Layout();
    abstract protected function Init();
    public function Main() {
      Init();   // 初期処理を記述
  }

}
 ?>
