<?php
namespace App\Controller;
class ElementTestController extends Base\BaseController {
    private $data;
    public function Initialize() {
        $this->viewBuilder()->setLayout('HomePage/element_test_layout');
        $this->set('footer', 'HomePage/Custom/CustomPageName/footer');
    }
    public function index() {
        $this->set('title', 'エレメント生成テストページ');
        $this->set('name', 'guest');
    }
}