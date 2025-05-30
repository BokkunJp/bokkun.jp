<?php

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

trait CommonTrait
{
    private const FINISH = 1;
    /**
     * sanitize
     *
     * ヌルバイト対策 (POST, GET)
     *
     * @param mixed $arr
     *
     * @return mixed
     */
    protected static function sanitize(mixed $arr): mixed
    {
        if (is_array($arr)) {
            return array_map([self::class, 'sanitize'], $arr);
        }

        if (!is_string($arr)) {
            return $arr;
        }

        return str_replace("\0", "", $arr);     //ヌルバイトの除去
    }

    /**
     * Pathクラスを経由して文字列をセット
     *
     * @param string $propertyName
     * @param string $pathUrl
     * 
     * @return string
     */
    private function setInPath(string $propertyName, string $pathUrl = ''): string
    {
        $setData = new \Path($pathUrl);

        $setData->add($propertyName);

        return $setData->get();
    }

    /**
     * createClient
     *
     * 所定のディレクトリまでのディレクトリ群を走査し、パスを生成する。
     *
     * @param string $target
     * @param string $src
     *
     * @return string
     */
    public function createClient($target, $src = ''): string
    {
        return createClient($target, $src);
    }

    /**
     * filterInputFix
     *
     * $_SERVER, $_ENVのための、filter_input代替処理。
     *
     *
     * @param  mixed $type
     * @param  mixed $variable_name
     * @param  int $filter
     * @param  mixed|null $options
     *
     * @return bool
     */
    protected function filterInputFix($type, $variable_name, int $filter = FILTER_DEFAULT, $options = null)
    {
        $checkTypes = [
            INPUT_GET,
            INPUT_POST,
            INPUT_COOKIE
        ];

        if ($options === null) {
            // No idea if this should be here or not
            // Maybe someone could let me know if this should be removed?
            $options = FILTER_NULL_ON_FAILURE;
        }

        if (searchData($type, $checkTypes) || filter_has_var($type, $variable_name)) {
            $ret = filter_input($type, $variable_name, $filter, $options);
        } elseif ($type == INPUT_SERVER && isset($_SERVER[$variable_name])) {
            $ret = filter_var($_SERVER[$variable_name], $filter, $options);
        } elseif ($type == INPUT_ENV && isset($_ENV[$variable_name])) {
            $ret = filter_var($_ENV[$variable_name], $filter, $options);
        } else {
            $ret = null;
        }

        return $ret;
    }

    /**
     * moldData
     *
     * データ調整。
     * (配列⇔特定のセパレータで区切られた文字列の相互変換)
     *
     * @param mixed $data
     * @param string $parameter
     *
     * @return mixed
     */
    public function moldData(mixed $data, string $parameter = ','): mixed
    {
        $ret = false;
        if (is_null($data)) {
            return false;
        }

        if (is_array($data)) {
            $ret = implode($parameter, $data);
        } elseif (is_string($data)) {
            $ret = explode($parameter, $data);
        }

        return $ret;
    }

    /**
     * output
     *
     * 出力用のメソッド。
     *
     * @param mixed $expression
     * @param boolean $formatFlg
     * @param boolean $indentFlg
     * @param boolean $dumpFlg
     * @param array $debug
     *
     * @return void
     */
    public function output(
        mixed $expression,
        bool $formatFlg = false,
        bool $indentFlg = true,
        bool $dumpFlg = false,
        array $debug = []
    ): int {
        return output($expression, $formatFlg, $indentFlg, $dumpFlg, $debug);
    }

    /**
     * createRandom
     *
     * 指定した桁数x2の乱数の生成。
     *
     * @param integer $length
     * @param string $type
     *
     * @return string
     */
    public function createRandom(int $length, string $type = 'security'): string
    {
        switch ($type) {
            case 'security':
                $bytes = bin2hex(openssl_random_pseudo_bytes($length));
                break;
            case 'sha1':
                $bytes = sha1($this->createRandom($length, 'mt_rand'));
                break;
            case 'md5':
                $bytes = md5($this->createRandom($length, 'mt_rand'));
                break;
            case 'uniq':
                $bytes = (string)uniqid($this->createRandom($length, 'mt_rand'));
                break;
            case 'mt_rand':
                $bytes = (string)mt_rand(0, $length);
                break;
            case 'random_bytes':
                $bytes = bin2hex(random_bytes($length));
                break;
            default:
                $bytes = $this->createRandom($length);
                break;
        }
        return $bytes;
    }

    /**
     * output
     *
     * デバッグ用のメソッド。
     * (outputのデバッグ設定用のラッパー)
     *
     * @param mixed $expression
     *
     * @return void
     */
    function debug(mixed $expression): void
    {
        debug($expression);
    }

    /**
     * makeQrCode
     *
     * QRコードを生成する。
     *
     * @param integer $size
     * @param string $contents
     * @param boolean $outputFlg
     *
     * @return void
     */
    public function makeQrCode(int $size, string $contents, bool $outputFlg = false)
    {
        $qrCode = new QrCode($contents, new Encoding('UTF-8'), ErrorCorrectionLevel::High, $size);

        $writer = new PngWriter();
        $qrWriter = $writer->write($qrCode);

        if ($outputFlg === true) {
            $this->output("<img src=\"". $qrWriter->getDataUri(). "\"></img>");
            return null;
        } else {
            return $qrWriter->getDataUri();
        }
    }

    /**
     * findFileName
     *
      * ファイル形式かチェックする
     *
     * @param  string $str
     *
     * @return bool
     */
    protected function findFileName(string $str, bool $rootOnly = true, bool $existFlg = false): bool
    {
        return findFileName($str, $rootOnly, $existFlg);
    }


    /**
     * searchData
     * in_arrayの代替処理。
     * (in_arrayは速度的に問題があるため、issetで対応する)
     *
     * @param  mixed $target
     * @param array $arrayData
     *
     * @return bool
     */
    public function searchData($target, array $arrayData): bool
    {
        return searchData($target, $arrayData);
    }

    /**
     * MoldImageConfig
     *
     * getImageSize関数で取得した配列を整形する。
     *
     * @param  array $imageName getImageSize関数で取得した配列
     *
     * @return array
     */
    public function moldImageConfig(array $imageConfig): array
    {
        return moldImageConfig($imageConfig);
    }

    /**
     * calcImageSize
     *
     *画像のサイズを計算する。
    *
    * @param string $imageName 画像名(画像パス含む)
    * @param string|int $imageSizeViewValue 画像サイズの表示桁数
    *
    * @return array|false
    */
    public function calcImageSize(string $imageName, string|int $imageSizeViewValue): array|false
    {
        return calcImageSize($imageName, $imageSizeViewValue);
    }

    /**
     * emptyValidate
     *
     * @param mixed $validate
     * @param string|null $word
     * @return boolean|null
     */
    public function emptyValidate(mixed $validate, ?string $word = null): ?bool
    {
        $v = null;

        switch ($word) {
        case 'isset':
            $v = isset($validate);
            break;
        case 'empty':
            $v = empty($validate);
            break;
        case 'is_null':
            $v = is_null($validate);
            break;
        case 'not':
            $v = !$validate;
            break;
        default:
            break;
    }

        return $v;
    }

    /**
     * checkMemory
     *
     * chckMemory関数を呼び出してから次に同じ関数を呼び出したタイミングまでの、メモリの差分を出力する
     *
     * @return void
     */
    public function checkMemory(): void
    {
        static $initialMemoryUse = null;

        if ($initialMemoryUse === null) {
            $initialMemoryUse = memory_get_usage();
        }

        output(number_format(memory_get_usage() - $initialMemoryUse), formatFlg:true);
    }

    protected function validMethod(string $methodName): bool
    {
        return method_exists($this, $methodName);
    }

    public function execMethod($methodName, ...$parameter)
    {
        if ($this->validMethod($methodName)) {
            $return = call_user_func_array([$this, $methodName], $parameter);
        } else {
            $return = false;
        }

        return $return;
    }
}
