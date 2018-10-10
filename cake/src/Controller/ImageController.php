<?php
namespace App\Controller;
class ImageController extends Base\BaseController {
    private $data;
    public function index() {
        $this->set('title', 'CakePHP3トップページ');
    }

    /*
     *   データの保存処理
     *   (画像は出さない)
     */
    public function save() {
      $data = $this->request->getData($this->name);
      if (isset($data)) {
          $this->set('responseData', $this->request->getData($this->name));
      } else {
          $this->set('responseData', 'false');
      }

      $this->autoRender = false;

      // 登録処理が終わると完了画面へ
    }

    public function send() {
        $this->setAction('save', $this->request->getData($this->name));
        $this->set('title', '受信サンプルページ');
        $this->render('send');
    }
}
