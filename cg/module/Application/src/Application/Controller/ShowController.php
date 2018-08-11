<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Application\Controller;

use Zend\View\Model\ViewModel;
use Zend\Debug\Debug;

use Application\Model\User;

class ShowController extends Base\BaseController {
    public function indexAction() {
      // Form
      $formService = $this->getServiceLocator()->get('Form');
      $elementService = $this->getServiceLocator()->get('Element');

      // smarty
      $this->smarty->setTemplate('application/show/index.tpl');
      $this->smarty->name = 'guest';
      return $this->smarty;
    }
}
