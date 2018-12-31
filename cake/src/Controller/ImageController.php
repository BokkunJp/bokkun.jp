<?php
namespace App\Controller;

use Cake\Routing\Router;
use Cake\Cache\Cache;                                // キャッシュ
use Cake\Event\Event;                                // CakePHP EventHandler (beforeFilterを使用するのに必要)
use Cake\Http\Middleware\CsrfProtectionMiddleware;   // CSRFミドルウェア

// Form
use App\Form\ImageForm;

class ImageController extends AppController {
    private $data;

    public function beforeFilter(Event $e) {
      parent::beforeFilter($e);
      $this->loadComponent('Config');
      $this->loadComponent('File');
      $this->loadComponent('Validate');
      $this->loadComponent('Csrf');

      $this->Config->Initialize([]);
    }
    public function index() {
      // $this->autoRender = false; // この行を追加
      $form = new ImageForm();
      $token = $this->request->getParam('_csrfToken');
      $validate = null;
      if ($this->request->is('post')) {
          if ($form->execute($this->request->getData())) {
              $validate = true;
              $option = ['type'=>'post', 'url'=>['controller'=>$this->Config->controller, 'action'=>'send']];
              $this->Flash->success('すぐにご連絡いたします。');
              // return $this->redirect(['action' => 'send', $this->request->getData()]);
          } else {
              $validate = false;
              $this->Flash->error('フォーム送信に問題がありました。');
          }
          // $this->autoRender = true;
    }
      $this->File->Test();
      $this->set('form', $form);
      $this->set('validate', $validate);
      $this->set('title', 'CakePHP3トップページ');
    }

    /*
     *   データの保存処理
     *   (画像は出さない)
     */
    public function save() {
      $data = $this->request->getData();

      $validate = $this->Validate->InputValidate();
      if ($this->Validate->ExistArrayValue($data)) {
          $this->set('responseData', $this->request->getData($this->name));
      } else {
          $this->redirect(['action' => 'index']);
      }

      $this->autoRender = false;

      // 登録処理が終わると完了画面へ
    }

    public function send() {
        $this->setAction('save');
        $this->set('responseData', $this->request->getData());
        $this->set('title', '保存完了');
        $this->render('send');
    }
}
