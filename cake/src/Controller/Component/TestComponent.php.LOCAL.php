<?php
namespace App\Controller\Component;
use Cake\Controller\Component;

class TestComponent extends Component {
  public $controller;
  public $action;

  public function Test($x1, $x2) {
    return $x1 * $x2;
  }

  public function initialize(array $config) {
      // コントローラとアクションの値をビューで取得できるようにする

      return true;
  }
}
