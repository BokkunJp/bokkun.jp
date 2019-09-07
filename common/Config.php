<?php

class Config {
    function __construct() {
        if (isset($base)) {
            $base = new PublicSetting\Setting();
        }
    }
}

class Header extends Config {

    // バージョン判定
    private function SetVersion()
    {
        switch ($_SERVER['SERVER_NAME']) {
            case 'bokkun.xyz':
            $ret = '-dev';
            break;
            case 'bokkun.jp':
            break;
            default:
            $ret = '-local';
            break;
        }
        return $ret;
    }
    public function GetVersion()
    {
        return $this->SetVersion();
    }
}

class Footer extends Config {
    private $start;
    private $end;
    private function SetYear($arg) {
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
    public function GetYear($arg='')
    {
        return $this->SetYear($arg);
    }
}