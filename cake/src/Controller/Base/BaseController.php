<?php
namespace App\Controller\Base;
class BaseController extends AppController {
    protected $controller;
    protected $action;
    /* 初期化処理 */
    public function Initialize() {
        // コントローラとアクションの値をビューで取得できるようにする
        $this->set('controller', $this->request->getParam('controller'));
        $this->set('action', $this->request->getParam('action'));

        // レイアウトをオリジナルのものに変更する
        $this->viewBuilder()->setLayout('HomePage/layout');

        return true;
    }

    /* DB接続関連 */
    public function Connect() {
        return false;
    }
}