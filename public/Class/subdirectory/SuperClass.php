<?php
class SuperClass {
    private $data;

    public function ActionFunction($funcName, $elmName=null) {
        if (!isset($funcName)) {
            echo $funcName. 'is null.';
            return null;
        }

        if (is_array($elmName)) {
          $this->ret = [];
          foreach ($elmName as $key => $elmAry) {
            $this->newFunction = $this->$funcName($elmAry);
          }
        } else {
          $this->newFunction = $this->$funcName($elmName);
        }

        if (!is_null($this->newFunction)) {
            return $this->newFunction;
        }
    }

    private function SetData($data) {
        $this->data = $data;
    }

    private function SetArrayData($elm, $data) {
      if (isset($elm)) {
        $this->SetData($this->data[$elm]);
      } else {
        $this->SetData($this->data);
      }
    }

    private function getData() {
        return $this->data;
    }

    private function getArrayData($elm) {
      var_dump($this->data);
          if (isset($elm)) {
            return $this->data[$elm];
          } else {
            $this->getData();
          }
    }
}
