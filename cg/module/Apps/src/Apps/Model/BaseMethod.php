<?php
namespace Apps\Model;

// 使用するモデル
use \Apps\Model\Base;

class BaseMethod extends Base{

    public function ShowSQL($select) {
        return $select->getSqlString($this->adapter->getPlatform());
    }

}
