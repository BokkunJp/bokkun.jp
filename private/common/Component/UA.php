<?php
namespace UA;
define('PC_design', 1);
define('SP_design', 2);

class UA {
    protected $ua;
    function __construct() {
        $this->setAgent();
    }
    public function setAgent() {
        if (!isset($this->ua)) {
            $this->ua = $_SERVER['HTTP_USER_AGENT'];
        }
    }

    public function getAgent() {
        return $this->ua;
    }

    public static function getConst() {
        return [[1 => 'PC_design'], [2 => 'SP_design']];
    }

    // device = 2 → スマホ
    // device = 1 → PC
    public function DesignJudge($device=null) {
        if ($this->judge('iPhone') || $this->judge('Android')) {
            return SP_design;
        } else {
            return PC_design;
        }
        if (isset($device)) {
            return $device;
        }
    }

    public function judge($device) {
        $ret = strpos($this->ua, $device);
        return $ret;
    }

}