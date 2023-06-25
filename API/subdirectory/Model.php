<?php
class API {
    private string $secretKey, $secretCode, $accessToken, $url = 'https://bokkun.jp/', $type = 'text/plain';
    private array $data;
    function __construct($secretKey, $secretCode)
    {
        $this->secretKey = $secretKey;       // シークレットキー
        $this->secretCode = $secretCode;    // シークレットコード
        $this->data = [];   // データを空でセット
    }

    public function SetUrl(string $type, string $url): void
    {
        $this->type = $type;
        $this->url = $url;
    }

    public function SetData(string $key, mixed $value)
    {
        $this->data[$key] = $value;
    }

    public function UnsetData($key = null)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
        } elseif (is_null($key)) {
            unset($this->data);
        } else {
            throw new Exception("Key is not valid.");
        }
    }

    public function SendData()
    {
        $handle = curl_init('hogfe');
        Output($handle);
    }
}

function ModelTest()
{
    return new API('hoge', 'hogehoge');
}
