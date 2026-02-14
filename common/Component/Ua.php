<?php

namespace Common\Important;

class Ua
{
    protected string $ua;
    private const IPHONE = 'iPhone', ANDROID = 'Android', PHONE = 'SMP', OTHER = 'PC';

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
            $this->ua = 'undefined';
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
     *
     * @return string
     */
    public function judgeDevice(): string
    {
        if ($this->judgePhoneDevice()) {
            $result = self::PHONE;
        } else {
            $result = self::OTHER;
        }

        return $result;
    }

    /**
     * judgePhoneDevice
     *
     * スマホのデバイスを判定する。
     *
     * @return string
     */
    public function judgePhoneDevice(): string
    {
        if ($this->judge('iPhone')) {
            $result = self::IPHONE;
        } elseif ($this->judge('Android')) {
            $result = self::ANDROID;
        } else {
            $result = false;
        }

        return $result;
    }

    /**
     * judege
     *
     * $deviceが入っているかを判定する
     *
     * @param string $device
     * @return string
     */
    public function judge($device): string
    {
        $ret = strpos($this->ua, $device);
        return $ret;
    }
}
