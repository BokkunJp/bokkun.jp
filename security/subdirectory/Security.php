<?php
namespace Security;

use PathApplication;

// Trait読み込み
$ioTraitPath = new PathApplication("ioTrait", COMMON_DIR);
$ioTraitPath->methodPath("addArray",[ "Trait", "IoTrait.php"]);
require($ioTraitPath->get());

class Security {
    private const DEFAULT_algorithm  = "aes-256-cbc", KEY_BYTES = 16;
    // private const DEFAULT_algorithm  = "aes-256-cbc-hmac-sha256";
    private string $algorithm, $iv, $key, $encrypt;
    private bool $resetFlg = false;
    private $data;

    use \IoTrait;
    
    public function convertResetFlg()
    {
        $this->resetFlg = !$this->resetFlg;
    }

    /**
     * __construct
     *
     * @param string|null $input
     */
    function __construct(?string $input = '')
    {
        // 入出力設定
        // $this->setProperty('data');

        if (!empty($input)) {
            $this->set($input);
        }

        if (is_null($this->get())) {
            $this->set('');
        }

        // 暗号化・復号化に関する設定
        $this->setalgorithm();
        $this->setOptions();
    }

    /**
     * setalgorithm
     * 
     * アルゴリズムの種類をセット
     *
     * @param string $algorithm
     * 
     * @return void
     */
    public function setalgorithm(string $algorithm = self::DEFAULT_algorithm): void
    {
        if (
            $algorithm != self::DEFAULT_algorithm
            && !searchData(
            $algorithm, openssl_get_cipher_methods()
            )
        ) {
            $algorithm = self::DEFAULT_algorithm;
        }

        $this->algorithm = $algorithm;
    }

    /**
     * resetOneTimeKey
     * 
     * キーをセットしなおす。
     *
     * @param int $bytes
     * 
     * @return void
     */
    protected function resetOneTimeKey(int $bytes = self::KEY_BYTES)
    {
        $this->key = bin2hex(random_bytes($bytes));
    }

    /**
     * setOption
     * 
     * キー、初期化ベクトルをセット。
     *
     * @return void
     */
    protected function setOptions()
    {
        $ivlen = openssl_cipher_iv_length($this->algorithm);
        $this->iv = openssl_random_pseudo_bytes($ivlen);
        $this->resetOneTimeKey($ivlen);
    }

    /**
     * encrypt
     * 
     * 暗号化
     *
     * @param string|null $input
     * 
     * @return string|false
     */
    public function encrypt(?string $input = null, bool $rowFlg = false): string|false
    {
        if (!is_null($input)) {
            $this->set($input);
        }

        if ($rowFlg) {
            $options = OPENSSL_RAW_DATA;
        } else {
            $options = 0;
        }

        $this->set(
            openssl_encrypt(
            $this->get(),
            $this->algorithm,
            $this->key,
            $options, 
            $this->iv
            )
        );

        return $this->get();
    }

    /**
     * Undocumented function
     *
     * @return string|false
     */
    public function errorOutput(): string|false
    {
        return openssl_error_string();
    }

    /**
     * decrypt
     * 
     * 復号化
     *
     * @return string|false
     */
    public function decrypt():string|false
    {
        $decrypt = openssl_decrypt(
            $this->get(),
            $this->algorithm,
            $this->key,
            0,
            $this->iv
        );

        // 一度複号した後は$this->keyをリセット(リセット設定している場合のみ)
        if ($this->resetFlg) {
            $this->resetOneTimeKey();
        }

        return $decrypt;
    }

    /**
     * createHash
     * 
     * ハッシュ化
     *
     * @param string $hashName
     * @param boolean $binFlg
     * @return string|false
     */
    public function createHash(string $hashName, $binFlg = false): string|false
    {
        if (!searchData($hashName, hash_algos())) {
            $hash = false;
        } else {
            $hash = hash($hashName, $this->get(), $binFlg);
        }

        return $hash;
    }

    /**
     * createHmacHash
     * Hmac式でハッシュ化
     *
     * @param string $hashHmacName
     * @param boolean $binFlg
     * @return string|false
     */
    public function createHmacHash(string $hashHmacName, $binFlg = false):string|false
    {
        if (!searchData($hashHmacName, hash_hmac_algos())) {
            $hash = false;
        } else {
            $hash = hash_hmac($hashHmacName, $this->get(), $this->key, $binFlg);
        }

        return $hash;
    }
}
