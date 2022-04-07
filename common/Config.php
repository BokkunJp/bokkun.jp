<?php

class Config
{
    public function __construct()
    {
    }
}

class Header extends Config
{
    /**
     * Serversion
     * バージョン判定
     *
     * @return string
     */
    private function SetVersion(): string
    {
        switch ($_SERVER['SERVER_NAME']) {
            case 'bokkun.xyz':
            $ret = '-dev';
            break;
            case 'bokkun.jp':
            $ret = '';
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

class Footer extends Config
{
    private $start;
    private $end;

    /**
     * SetYear
     *
     * @param string $arg
     * @return string|array
     */
    private function SetYear(string $arg): string|array
    {
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

    /**
     * GetYear
     *
     * @param string $arg
     * @return string
     */
    public function GetYear(string $arg='')
    {
        return $this->SetYear($arg);
    }
}
