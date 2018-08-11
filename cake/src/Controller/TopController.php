<?php
namespace App\Controller;
class TopController extends Base\BaseController {
    private $data;
    public function index() {
        $this->set('title', 'CakePHP3トップページ');
    }
    public function send() {
        $this->set('title', '受信サンプルページ');
        $data = $this->request->getData($this->name);
        if (isset($data)) {
            $this->set('responseData', $this->request->getData($this->name));
        } else {
            $this->set('responseData', 'false');            
        }
    }
}