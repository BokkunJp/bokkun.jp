<?php
namespace Apps\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Apps\Form\FormSet;
use Apps\Form\ElementSet;
use Zend\Debug\Debug;

class MypageController  extends Base\BaseController {
    public function adminAction() {

    }
    public function indexAction() {
        return new ViewModel();
    }
}
