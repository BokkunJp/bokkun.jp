
<?php
class simplexml_parse
{
    private $xmlData;
    public function __construct($xmlData)
    {
        $this->xmlData = $xmlData;
    }

    /**
     * GetChildren
     * @abstract すべてのノードおよび子ノードのデータを取得する。
     *
     * @param [simpleXMLElement] void
     * @return array
     */
    public function GetAll()
    {
        $retArray = [];
        foreach ($this->xmlData->children() as $_key => $_val) {
            $retArray += $this->GetChildren($_key);
        }
        return $retArray;
    }

    /**
     * GetChildren
     * @abstract 指定したノードのすべての子ノードのデータを取得する。
     *
     * @param [simpleXMLElement] $xmlData
     * @param [string] $elmName
     * @return array|boolean
     */
    public function GetChildren($elmName)
    {
        if (!is_string($elmName)) {
            return false;
        }

        $retArray = [];
        $retArray[$elmName] = [];
        if (isset($this->xmlData->$elmName)) {
            if (empty($this->xmlData->$elmName->children())) {
                $retArray[$elmName] = $this->GetElement($this->xmlData, $elmName);
            } else {
                foreach ($this->xmlData->$elmName->children() as $_key => $_val) {
                    $childData = $this->xmlData->$elmName->children();
                    $retArray[$elmName] += $this->GetChild($childData, $_key);
                }
            }
        } else {
            return false;
        }

        return $retArray;
    }

    private function GetChild($xmlData, $elmName)
    {
        if (!is_string($elmName)) {
            return false;
        }

        $retArray = [];

        if (isset($xmlData->$elmName)) {
            if (empty($xmlData->$elmName->children())) {
                $retArray[$elmName] = $this->GetElement($xmlData, $elmName);
            } else {
                $childData = $xmlData->$elmName->children();
                $retArray[$elmName] = $this->GetChild($childData, $childData->getName());
            }
        } else {
            return false;
        }

        return $retArray;
    }

    private function GetElement($xmlData, $elmName)
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
