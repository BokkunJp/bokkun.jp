<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Controller;

use Zend\Debug\Debug;

// smarty
use ZSmarty\SmartyModel;

class DebugController extends Base\BaseController {
    public function indexAction() {
        $debug = new Debug();
        // smarty
        $smarty = new SmartyModel();
        $smarty->setTemplate('application/debug/index.tpl');
        $smarty->name = 'Debug';
        return $smarty;
    }
}
