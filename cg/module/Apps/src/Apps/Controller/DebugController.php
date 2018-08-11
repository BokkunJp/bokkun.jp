<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Apps\Controller;

use Zend\Debug\Debug;

// smarty
use ZSmarty\SmartyModel;

class DebugController extends BaseController {
    public function indexAction() {
        $debug = new Debug();
        // smarty
        $smarty = new SmartyModel();
        $smarty->setTemplate('apps/debug/index.tpl');
        $smarty->name = 'Debug2';
        return $smarty;
    }
}
