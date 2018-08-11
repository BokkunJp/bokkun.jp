<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\View\Model\ViewModel;
use \Zend\Debug\Debug;

// smarty
use ZSmarty\SmartyModel;

class DatabaseController extends Base\BaseController {
    public function indexAction() {
        // Model
        $usersTable = $this->getServiceLocator()->get('UsersTable');

        // Form
        $formService = $this->getServiceLocator()->get('Form');
        $elementService = $this->getServiceLocator()->get('Element');

        // // View
        // $viewModel = new ViewModel();

        // smarty
        $this->smarty->setTemplate('application/database/index.tpl');

        $ary = [1, 2, 3, 4, 'a' => 'abc'];
        $userData = $usersTable->getUserById(1);
        $this->smarty->id = $usersTable->getID(1);
        $this->smarty->user_mail = $userData->email;

        return $this->smarty;
    }

}
