<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Apps\Controller\Base;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Zend\Debug\Debug;

// smarty
use ZSmarty\SmartyModel;

use Application\Model\Base as Base;

class BaseController extends AbstractActionController
{
    public $smarty;
    public $debug;
    public $viewModel;

    function __construct() {

      $this->debug = new Debug();
      $this->layout = new Layout();
      $this->viewModel = new ViewModel(['debug' => $this->debug]);
      // smarty
      $this->smarty = new SmartyModel();
    }

    public function Get($name) {
        return $this->getServiceLocator()->get($name);
    }
}
