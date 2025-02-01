
<?php
class parseSimpleXml
{
    private $xmlData;
    public function __construct($xmlData)
    {
        $this->xmlData = $xmlData;
    }

    /**
     * getAll
     * @abstract すべてのノードおよび子ノードのデータを取得する。
     *
     * @param [simpleXMLElement] void
     * @return array
     */
    public function getAll()
    {
        $retArray = [];
        foreach ($this->xmlData->children() as $_key => $_val) {
            $retArray += $this->getChildren($_key);
        }
        return $retArray;
    }

    /**
     * getChildren
     * @abstract 指定したノードのすべての子ノードのデータを取得する。
     *
     * @param [simpleXMLElement] $xmlData
     * @param [string] $elmName
     * @return array|boolean
     */
    public function getChildren($elmName)
    {
        if (!is_string($elmName)) {
            return false;
        }

        $retArray = [];
        $retArray[$elmName] = [];
        if (isset($this->xmlData->$elmName)) {
            if (empty($this->xmlData->$elmName->children())) {
                $retArray[$elmName] = $this->getElement($this->xmlData, $elmName);
            } else {
                foreach ($this->xmlData->$elmName->children() as $_key => $_val) {
                    $childData = $this->xmlData->$elmName->children();
                    $retArray[$elmName] += $this->getChild($childData, $_key);
                }
            }
        } else {
            return false;
        }

        return $retArray;
    }

    private function getChild($xmlData, $elmName)
    {
        if (!is_string($elmName)) {
            return false;
        }

        $retArray = [];

        if (isset($xmlData->$elmName)) {
            if (empty($xmlData->$elmName->children())) {
                $retArray[$elmName] = $this->getElement($xmlData, $elmName);
            } else {
                $childData = $xmlData->$elmName->children();
                $retArray[$elmName] = $this->getChild($childData, $childData->getName());
            }
        } else {
            return false;
        }

        return $retArray;
    }

    private function getElement($xmlData, $elmName)
    {
        if (!is_string($elmName)) {
            return false;
        }

        if (isset($xmlData->$elmName)) {
            $ret = $xmlData->$elmName->__toString();
        } else {
            return false;
        }

        return $ret;
    }
}
