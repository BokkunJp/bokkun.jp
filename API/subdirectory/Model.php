<?php

class API
{
    private string $secretKey;
    private string $secretCode;
    private string $accessToken;
    private string $url = 'https://bokkun.jp/';
    private string $type = 'text/plain';
    private array $data;
    public function __construct($secretKey, $secretCode)
    {
        $this->secretKey = $secretKey;       // シークレットキー
        $this->secretCode = $secretCode;    // シークレットコード
        $this->data = [];   // データを空でセット
    }

    public function setUrl(string $type, string $url): void
    {
        $this->type = $type;
        $this->url = $url;
    }

    public function setData(string $key, mixed $value)
    {
        $this->data[$key] = $value;
    }

    public function unsetData($key = null)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        } elseif (is_null($key)) {
            unset($this->data);
        } else {
            throw new Exception("Key is not valid.");
        }
    }

    public function sendData()
    {
        $handle = curl_init('hogfe');
        output($handle);
    }
}

function modelTest()
{
    return new API('hoge', 'hogehoge');
}
