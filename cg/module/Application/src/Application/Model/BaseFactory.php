<?php
namespace Application\Model;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BaseFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $base = new Base();
        $base->setServiceLocator($serviceLocator);
        return $base;
    }

    public function get($modelName)
    {
        // module.config.phpにモデルのサービス定義が存在したら、それをモデルクラス名として取得
        $moduleConfig = $serviceLocator->get('Config');
        if (isset($moduleConfig['models'][$modelName])) {
            $modelName = $moduleConfig['models'][$modelName];
        }
        $model = new $modelName();
        return $model;
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}