<?php
namespace UA;
define('PC_design', 1);
define('SP_design', 2);

class UA {
    protected $ua;
    function __construct() {
        $this->setAgent();
    }
    public function SetAgent() {
        if (!isset($this->ua) && isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->ua = $_SERVER['HTTP_USER_AGENT'];
        }
    }

    public function UpdateAgent() {
            return $this->ua = $_SERVER['HTTP_USER_AGENT'];
    }

    public function GetAgent() {
        return $this->ua;
    }

    public static function GetConst() {
        return [[1 => 'PC_design'], [2 => 'SP_design']];
    }

    // device = 2 → スマホ
    // device = 1 → PC
    public function DesignJudge($device=null) {
        if ($this->Judge('iPhone') || $this->Judge('Android')) {
            return SP_design;
        } else {
            return PC_design;
        }
    }

    public function Judge($device) {
        $ret = strpos($this->ua, $device);
        return $ret;
    }

}