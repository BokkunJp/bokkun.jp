<?php

namespace Error\Important;

define('PC_design', 1);
define('SP_design', 2);

class UA
{
    protected $ua;
    public function __construct()
    {
        $this->setAgent();
    }
    public function setAgent()
    {
        if (!isset($this->ua) && isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->ua = $_SERVER['HTTP_USER_AGENT'];
        }
    }

    public function getAgent()
    {
        return $this->ua;
    }

    // device = 2 → スマホ
    // device = 1 → PC
    public function judgeDevice($device=null)
    {
        if ($this->judge('iPhone')|| $this->judge('Android')) {
            return 2;
        } else {
            return 1;
        }
        if (isset($device)) {
            return $device;
        }
    }

    public function judge($device)
    {
        $ret = strpos($this->ua, $device);
        return $ret;
    }
}
