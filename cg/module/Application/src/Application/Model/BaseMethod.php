<?php
namespace Application\Model;

// 使用するモデル
use \Application\Model\Base;

class BaseMethod extends Base{

    public function ShowSQL($select) {
        return $select->getSqlString($this->adapter->getPlatform());
    }

}
