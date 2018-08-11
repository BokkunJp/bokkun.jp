<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Application\Form\FormSet;
use Application\Form\ElementSet;
use Zend\Debug\Debug;

// smarty
use ZSmarty\SmartyModel;

class MypageController  extends Base\BaseController {
    public function adminAction() {

    }

    public function indexAction() {
      $this->smarty->setTemplate('application/mypage/index.tpl');

      $this->smarty->name = 'test';

      return $this->smarty;
    }
}
