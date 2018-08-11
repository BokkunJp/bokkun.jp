<?php
class Base extends Accesser{
    private $funcName;
    public function CallFunc($funcNameElm) {
        $this->funcName = $funcNameElm();
    }

}

class Accesser {
    private $value;
    protected function ViewValue() {
        echo $this->value;
    }
    
    protected function SetValue($val) {
        $this->value = $val;
    }

    protected function GetValue() {
        return $this->value;
    }

}
