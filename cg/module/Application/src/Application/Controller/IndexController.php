<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Debug\Debug;

class IndexController extends Base\BaseController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    public function helloAction()
    {
        $debug = new Debug();
        $form = $this->getServiceLocator()->get('Form');
        $viewModel =  new ViewModel();
        $viewModel->form = $form;
        return $viewModel;
    }
}
