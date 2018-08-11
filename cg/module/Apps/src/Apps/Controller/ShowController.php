<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace CGApps1\Controller;

use Zend\View\Model\ViewModel;
use Zend\Debug\Debug;

use Application\Model\User;

class ShowController extends Base\BaseController {
    public function indexAction() {
        $viewModel = new ViewModel();
        return new $viewModel;
    }
}
