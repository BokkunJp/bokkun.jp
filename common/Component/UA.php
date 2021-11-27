<?php
namespace UA;
class UA {
    protected $ua;
    function __construct() {
        $this->setAgent();
    }
    public function setAgent() {
        if (!isset($this->ua) && isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->ua = $_SERVER['HTTP_USER_AGENT'];
        }
    }

    public function getAgent() {
        return $this->ua;
    }

    // device = 2 → スマホ
    // device = 1 → PC
    public function DesignJudge($device=null) {
        if ($this->judge('iPhone')|| $this->judge('Android')) {
            return 2;
        } else {
            return 1;
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
