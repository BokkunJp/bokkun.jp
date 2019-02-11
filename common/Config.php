<?php
class Config {
    private $start;
    private $end;
    private $tiwtter;
    
    function __construct() {
        if (isset($base)) {
            $base = new PublicSetting\Setting();
        }
    }
}

class Header extends Config {
}

class Footer extends Config {
    public function GetDate($arg, $elm) {
        switch ($arg) {
            case 'year':
                $this->GetYear($elm);
            break;
            default:
                break;
        }
    }
    public function GetYear($arg='') {
        $time = new DateTime();
        $this->end = $time->format('Y');
        $this->start = '2016';
        switch ($arg) {
            case 'start':
                $ret = $this->start;
                break;
            case 'end':
                $ret = $this->end;
                break;
            default:
                $ret = ['start' => $this->start, 'end' => $this->end];
                break;
        }
        return $ret;
    }
}