<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Apps\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;
use \Zend\Debug\Debug;

// smarty
use ZSmarty\SmartyModel;

class DatabaseController extends Base\BaseController {
    protected $usertable;
    public function indexAction() {

        // Model
        $usersTable = $this->getServiceLocator()->get('UsersTable');

        // Form
        $formService = $this->getServiceLocator()->get('Form');
        $elementService = $this->getServiceLocator()->get('Element');

        // View
        $viewModel = new ViewModel();

        // smarty
        $smarty = new SmartyModel();
        $smarty->setTemplate('apps/database/index.tpl');

        $ary = [1, 2, 3, 4, 'a' => 'abc'];
        $userData = $usersTable->getUserById(1);
        $smarty->id = $usersTable->getID(1);
        $smarty->user_mail = $userData->email;
        $smarty->contents = 'Thanks!!';

        return $smarty;
    }

}
