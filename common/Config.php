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
    private function setVersion(): string
    {
        $serverName = $_SERVER['SERVER_NAME'] ?? null;
        switch ($serverName) {
            case 'bokkun.org':
                $ret = '-dev';
                break;
            case 'bokkun.jp':
                $ret = '';
                break;
            case 'bokkun.info':
                $ret = '-v2';
                break;
            default:
                $ret = '-local';
                break;
        }
        return $ret;
    }

    public function getVersion()
    {
        return $this->setVersion();
    }
}

class Footer extends Config
{
    private CONST START = '2016';
    private string $end;

    /**
     * SetYear
     * 
     * 開始年、終了年をセットする
     *
     * @param string $arg
     * @return string|array
     */
    private function setYear(string $arg): string|array
    {
        $time = new DateTime();
        $this->end = $time->format('Y');
        switch ($arg) {
            case 'start':
                $ret = self::START;
                break;
            case 'end':
                $ret = $this->end;
                break;
            default:
                $ret = ['start' => self::START, 'end' => $this->end];
                break;
        }
        return $ret;
    }

    /**
     * GetYear
     * 
     * 開始年または終了年(またはその両方)を取得する
     *
     * @param string $arg
     * @return string
     */
    public function getYear(string $arg='')
    {
        return $this->setYear($arg);
    }
}
