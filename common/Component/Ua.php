<?php

namespace Common\Important;

class Ua
{
    protected string $ua;

    public function __construct()
    {
        $this->setAgent();
    }

    /**
     * setAgent
     *
     * エージェント情報をセットする。
     *
     * @return void
     */
    public function setAgent(): void
    {
        if (!isset($this->ua) && isset($_SERVER['HTTP_USER_AGENT'])) {
            $this->ua = $_SERVER['HTTP_USER_AGENT'];
        } else {
            $this->ua = 'undfeined';
        }
    }

    /**
     * getAgent
     *
     * エージェント情報を取得する。
     *
     * @return string
     */
    public function getAgent(): string
    {
        return $this->ua;
    }

    /**
     * judgeDevice
     *
     * デバイスを判定する。
     * (1→iPhone, 2→Android)
     *
     * @return integer
     */
    public function judgeDevice(): int
    {
        if ($this->judge('iPhone')|| $this->judge('Android')) {
            return 2;
        } else {
            return 1;
        }
    }

    public function judge($device)
    {
        $ret = strpos($this->ua, $device);
        return $ret;
    }
}
