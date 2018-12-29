<?php
namespace App\Controller\Component;

use Cake\Controller\Component;

class ConfigComponent extends Component {
  public $controller;
  public $action;

  public function Initialize(array $config) {
    // コントローラとアクションの情報を参照する
    $controller = $this->_registry->getController();
    $this->controller = $this->request->getParam('controller');
    $this->action = $this->request->getParam('action');

    $controller->set('controller', $this->controller);
    $controller->set('action', $this->action);

    // レイアウトをオリジナルのものに変更する
    $controller->viewBuilder()->setLayout('HomePage/layout');
  }
}
