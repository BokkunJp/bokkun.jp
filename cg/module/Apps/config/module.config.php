<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Apps;
return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'apps' => array(
//                'type'    => 'Literal',
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/cgApps2/[:controller[/:action]]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Apps\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'invokables' => array(
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'Smarty' => 'ZSmarty\Factory\StrategyFactory',
            'Base' => 'Apps\Model\BaseFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Apps\Controller\Base' => Controller\BaseController::class,
            'Apps\Controller\Debug' => Controller\DebugController::class,
            'Apps\Controller\Database' => Controller\DatabaseController::class,
            'Apps\Controller\Show' => Controller\ShowController::class,
            'Apps\Controller\MyPage' => Controller\MyPageController::class,
            'Apps\Controller\Error' => Controller\ErrorController::class,
        ),
    ),
    'view_manager' => array(
        'strategies' => array('Smarty'),
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        // レイアウトはApplicationのtemplate_mapを使用
        // 'template_map' => array(
        //     'layout/layout'           => __DIR__ . '/../view/layout/homepage/layout.phtml',
        //     'Apps/index/index' => __DIR__ . '/../view/apps/index/index.tpl',
        //     'error/404'               => __DIR__ . '/../view/error/homepage/404.tpl',
        //     'error/index'             => __DIR__ . '/../view/error/homepage/index.php',
        // ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
