<?php
/*
 *  AdapterやGateWayなどの
 * データベース操作をする前に必要な処理
 * 
 */
namespace Apps\Model;

// 使用するクラス
use Zend\ServiceManager\ServiceLocatorInterface;
use \Zend\Db\TableGateway\TableGateway;
use \Zend\Db\Adapter\AdapterAwareInterface;
use \Zend\Db\Adapter\Adapter;

// 使用するモデル
use \Apps\Model\BaseFactory;

class Base extends BaseFactory


{
    protected $serviceLocator;
    protected $tableGateway;
    protected $adapter;

    // 初期処理
    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function getAdapter() {
        $this->adapter = $this->tableGateway->getAdapter();        
    }


}