<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Apps;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;

// Model
use Apps\Model\UsersTable;
use Apps\Model\Users;

// FORM
use Apps\Form\FormSet\FormSet as FormSet;
use Apps\Form\ElementSet\ElementSet as ElementSet;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    // 追加
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'UsersTable'   =>  function($sm) {
                    $UsersTableGateway = $sm->get('UsersTableGateway');     // ここでDBを読み込む
                    $UsersTable = new UsersTable($UsersTableGateway);
                    return $UsersTable;
                },
                'UsersTableGateway'  =>  function($sm) {
                    $dbAdapter = $sm->get('\Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Users());
                    return new TableGateway(
                        'users', $dbAdapter, null, $resultSetPrototype
                    );
                },
                'AaaTable'   =>  function($sm) {
                    $AaaTableGateway = $sm->get('AaaTableGateway');     // ここでDBを読み込む
                    $AaaTable = new AaaTable($AaaTableGateway);
                    return $AaaTable;
                },
                'AaaTableGateway'  =>  function($sm) {
                    $dbAdapter = $sm->get('\Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Aaa());
                    return new TableGateway(
                        'aaa', $dbAdapter, null, $resultSetPrototype
                    );
                },
                'Form' => function ($sm) {
                    return new FormSet();
                },
                'Element' => function ($sm) {
                    return new ElementSet();
                },
            ),
        );
    }
}
