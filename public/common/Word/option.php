<?php
    require_once 'wordDefine.php';                            // 選択肢文言ファイルをインクルード
    
    class Option {
        private $op1;
        private $op2;
        private $op3;

        public function __construct() {
            $this->op1 = "選択肢1";
            $this->op2 = "選択肢2";
            $this->op3 = "選択肢3";
        }

        public function getArray() {
            $ary = array();
            foreach ($this as $key => $value) {
                $ary[$key] = $value;
            }
            return $ary;
        }
    }

    class ChildOption extends Option {
        private $op1_1;
        private $op1_2;
        public function __construct() {
            parent::__construct();
            $this->op1_1 = "子選択肢1";
            $this->op1_2 = "子選択肢2";
        }

        public function getValue($elm, $parent_flg=null) {
        }
    }
