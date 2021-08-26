<?php
SetPlugin('qr-code');

use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

trait CommonTrait
{

    // ヌルバイト対策 (POST, GET)
    protected function Sanitize($arr)
    {
        if (!is_string($arr)) {
            return $arr;
        }

        if (is_array($arr)) {
            return array_map('Sanitize', $arr);
        }
        return str_replace("\0", "", $arr);     //ヌルバイトの除去
    }

    /**
     * CreateClient
     * 所定のディレクトリまでのディレクトリ群を走査し、パスを生成する。
     *
     * @param  string $target
     *
     * @return bool
     */
    public function CreateClient($target, $src = '')
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
            $clientPath = AddPath($clientPath, $_client);
        }

        return $clientPath;
    }
    /**
     * CheckToken
     * Post値とセッション値のチェック
     *
     *
     * @param  string $tokenName
     * @param  boolean $chkFlg
     *
     * @return bool
     */
    public function CheckSession($SessionName, $chkFlg)
    {
        $input = CommonSetting\Setting::GetPost($SessionName);
        $session = new CommonSetting\Session();

        if ($chkFlg === true) {
            echo 'デバッグ用<br/>';
            echo 'post: ' . $input . '<br/>';
            echo 'session: ' . $session->Read($SessionName) . '<br/><br/>';
        }

        if (is_null($input) || $input === false || is_null($session->Read($SessionName)) || !hash_equals($session->Read($SessionName), $input)) {
            return false;
        }

        return true;
    }

    /**
     * filter_input_fix
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
    protected function filter_input_fix($type, $variable_name, $filter = FILTER_DEFAULT, $options = NULL)
    {
        $checkTypes = [
            INPUT_GET,
            INPUT_POST,
            INPUT_COOKIE
        ];

        if ($options === NULL) {
            // No idea if this should be here or not
            // Maybe someone could let me know if this should be removed?
            $options = FILTER_NULL_ON_FAILURE;
        }

        if (in_array($type, $checkTypes) || filter_has_var($type, $variable_name)) {
            $ret = filter_input($type, $variable_name, $filter, $options);
        } else if ($type == INPUT_SERVER && isset($_SERVER[$variable_name])) {
            $ret = filter_var($_SERVER[$variable_name], $filter, $options);
        } else if ($type == INPUT_ENV && isset($_ENV[$variable_name])) {
            $ret = filter_var($_ENV[$variable_name], $filter, $options);
        } else {
            $ret = NULL;
        }

        return $ret;
    }

    /**
     * MoldData
     *
     *
     */
    public function MoldData($data, $parameter = ',')
    {
        $ret = false;
        if (is_null($data)) {
            return false;
        }

        if (is_array($data)) {
            $ret = implode($parameter, $data);
        } else if (is_string($data)) {
            $ret = explode($parameter, $data);
        }

        return $ret;
    }
    /**
     * Output
     *
     * @param [mixed] $expression
     * @param boolean $formatFlg
     * @param boolean $indentFlg
     * @param array $debug
     * @return void
     */
    public function Output($expression, $formatFlg = false, $indentFlg = true, array $debug = [])
    {
        if ($formatFlg === true) {
            print_r("<pre>");
            print_r($expression);
            print_r("</pre>");
        } else {
            print_r($expression);
            if ($indentFlg === true) {
                print_r(nl2br("\n"));
            }
        }

        $debugMessage = DEBUG_MESSAGE_SOURCE;
        if (!empty($debug)) {
            $debugTrace = debug_backtrace();
            $debugValidate = $this->DebugValidate($debug, $debugTrace);
            if (!empty($debugValidate)) {
                $errScript = new BasicTag\ScriptClass();
                foreach ($debugValidate as $_DEBUG_KEY) {
                    if ($debugMessage[$_DEBUG_KEY]) {
                        $errScript->Alert($debugMessage[$_DEBUG_KEY]);
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
    }

    private function DebugValidate(array $debug, array $debugTrace)
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

    public function CreateRandom(int $length, string $type = 'security')
    {
        switch ($type) {
            case 'security':
                $bytes = bin2hex(openssl_random_pseudo_bytes($length));
                break;
            case 'sha1':
                $bytes = sha1($this->CreateRandom($length, 'mt_rand'));
                break;
            case 'md5':
                $bytes = md5($this->CreateRandom($length, 'mt_rand'));
                break;
            case 'uniq':
                $bytes = uniqid($this->CreateRandom($length, 'mt_rand'));
                break;
            case 'mt_rand':
                $bytes = mt_rand(0, $length);
                break;
            case 'random_bytes':
                $bytes = bin2hex(random_bytes($length));
                break;
            default:
                $bytes = $this->CreateRandom($length);
                break;
        }
        return $bytes;
    }

    public function SetPlugin($name) {
        if (is_array($name)) {
            foreach ($name as $_dir) {
                $targetName = SetPlugin($_dir);
            }
        }

        if (is_dir(AddPath(PLUGIN_DIR, $name))) {
            IncludeDirctories(AddPath(PLUGIN_DIR, $name));
        }
    }

    public function SetAllPlugin() {
        $addDir = scandir(PLUGIN_DIR);

        foreach ($addDir as $_key => $_dir) {
            if (strpos($_dir, '.') !== false || strpos($_dir, '..')  !== false) {
                unset($addDir[$_key]);
            }
        }

        SetPlugin($addDir);
    }

    /**
     * MakeQrCode
     *
     * @param [string] $qrCodeName
     * @return void
     */
    public function MakeQrCode(int $size, string $contents, bool $outputFlg = false) {
        $qrCode = new QrCode('qr-sample');
        $qrCode->setEncoding(new Encoding('UTF-8'));
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh());
        $qrCode->setSize($size);
        $qrCode->setData($contents);

        $writer = new PngWriter();
        $qrWriter = $writer->write($qrCode);

        if ($outputFlg === true) {
            $this->Output("<img src=\"". $qrWriter->getDataUri(). "\"></img>");
        } else {
            return $qrWriter->getDataUri();
        }

    }

}
