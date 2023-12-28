<?php

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
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
    protected function sanitize(mixed $arr): mixed
    {
        if (!is_string($arr)) {
            return $arr;
        }

        if (is_array($arr)) {
            return array_map('sanitize', $arr);
        }
        return str_replace("\0", "", $arr);     //ヌルバイトの除去
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
        if (empty($src)) {
            $srcPath = getcwd();
        } else {
            $srcPath = $src;
        }

        $clientPath = "";
        $clientAry = [];

        if ($srcPath !== dirname($srcPath)) {
            while (1) {
                $clientAry[] = basename($srcPath);
                $srcPath = dirname($srcPath);
                if (strcmp(basename($srcPath), $target)) {
                    break;
                }
            }
        } else {
            $clientAry[] = $target;
        }

        $clientAry = array_reverse($clientAry);

        foreach ($clientAry as $_client) {
            $client = new Path($clientPath);
            $client->add($_client);
            $clientPath = $client->get();
        }

        return $clientPath;
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
        if ($formatFlg === true) {
            print_r("<pre>");
            if ($dumpFlg === true) {
                var_dump($expression);
            } else {
                print_r($expression);
            }
            print_r("</pre>");
        } else {
            print_r($expression);
            if ($indentFlg === true) {
                print_r(nl2br("\n"));
            }
        }

        if (!empty($debug)) {
            $debugMessage = DEBUG_MESSAGE_SOURCE;
            $debugTrace = debug_backtrace();
            $debugValidate = $this->debugValidate($debug, $debugTrace);
            if (!empty($debugValidate)) {
                $errScript = new Public\Important\ScriptClass();
                foreach ($debugValidate as $_DEBUG_KEY) {
                    if ($debugMessage[$_DEBUG_KEY]) {
                        $errScript->alert($debugMessage[$_DEBUG_KEY]);
                    }
                }
                return -1;
            }

            $layer = $debug['layer'] - 1;

            if (isset($debug['mode'])) {
                switch ($debug['mode']) {
                    case 'source':
                        echo "<pre>source: " . $debugTrace[$layer]['file'] . "</pre>";
                        break;
                    case 'line':
                        echo "<pre>line: " . $debugTrace[$layer]['line'] . "</pre>";
                        break;
                    case 'function':
                        echo "<pre>function: " . $debugTrace[$layer]['function'] . "</pre>";
                        break;
                    default:
                        echo "<pre>source: " . $debugTrace[$layer]['file'] . "</pre>";
                        echo "<pre>line: " . $debugTrace[$layer]['line'] . "</pre>";
                        echo "<pre>function: " . $debugTrace[$layer]['function'] . "</pre>";
                        break;
                }
            }
        }

        return self::FINISH;
    }

    /**
     * DebugValitate
     *
     * デバッグ出力時のバリデーション。
     *
     * @param array $debug
     * @param array $debugTrace
     *
     * @return array
     */
    private function debugValidate(array $debug, array $debugTrace): array
    {
        $validate = [];

        if (!isset($debug['layer']) || !isset($debug['mode'])) {
            $validate[] = "ERR_DEBUG_COND";
            return $validate;
        }

        if (!is_string($debug['mode'])) {
            $validate[] = "SETTING_DEBUG_TRACE";
        }

        if ($debug['layer'] - 1 < 0) {
            $validate[] = "ERR_DEBUG_FEW_TRACE_LAYER";
        }

        if ($debug['layer'] - 1 > count($debugTrace)) {
            $validate[] = "ERR_DEBUG_TOO_TRACE_LAYER";
        }

        return $validate;
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
        output($expression, true, true, true);
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
        $qrCode = new QrCode('qr-sample');
        $qrCode->setEncoding(new Encoding('UTF-8'));
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());
        $qrCode->setSize($size);
        $qrCode->setData($contents);

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
        $filipData = array_flip($arrayData);

        $ret = false;

        // 指定した名称のディレクトリが存在するかどうか
        if (isset($filipData[$target])) {
            $ret = true;
        }

        return $ret;
    }

    /**
     * MoldImageConfig
     *
     * getImageSize関数で取得した配列を整形する。
     *
     * @param  array|string $imageName 画像名(画像パス含む)
     *
     * @return array
     */
    public function moldImageConfig($imageConfig): array
    {
        $ret = [];
        if (is_array($imageConfig)) {
            $params = ['width', 'height', 'type', 'html'];

            foreach ($imageConfig as $_key => $_imageConfig) {
                if (!empty($params[$_key]) && isset($params[$_key])) {
                    $ret[$params[$_key]] = $_imageConfig;
                }
            }
        }

        return $ret;
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
        // 画像が存在しない場合はfalseを返す
        if (!file_exists($imageName) || !exif_imagetype($imageName)) {
            return false;
        }
        $imageConfig = getimagesize($imageName);
        $imageSize = filesize($imageName);
        $imageSizeUnitArray = ['K', 'M', 'G', 'T', 'P'];
        $imageSizeUnit = '';
        foreach ($imageSizeUnitArray as $_imageSizeUnit) {
            if ($imageSize >= IMAGE_MAX_VALUE) {
                $imageSize = bcdiv($imageSize, IMAGE_MAX_VALUE, $imageSizeViewValue);
                $imageSizeUnit = $_imageSizeUnit;
            } else {
                break;
            }
        }
        $ret = ['size' => $imageSize, 'sizeUnit' => $imageSizeUnit];
        $ret = array_merge(moldImageConfig($imageConfig), $ret);

        return $ret;
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
     * メモリを可視化する
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
