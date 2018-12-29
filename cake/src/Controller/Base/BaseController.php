<?php
namespace App\Controller\Base;
class BaseController extends AppController {
    protected $controller;
    protected $action;
    /* 初期処理 */
    public function Initialize() {
      // コントローラとアクションの情報を参照する
      $this->set('controller', $this->request->getParam('controller'));
      $this->set('action', $this->request->getParam('action'));

      // レイアウトをオリジナルのものに変更する
      $this->viewBuilder()->setLayout('HomePage/layout');

        return true;
    }
}
