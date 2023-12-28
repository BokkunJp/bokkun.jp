<?php

// タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');

require_once __DIR__ . DIRECTORY_SEPARATOR . "Initialize"  . DIRECTORY_SEPARATOR .  "Path.php";
require_once __DIR__ . DIRECTORY_SEPARATOR . "Initialize"  . DIRECTORY_SEPARATOR .  "PathApplication.php";

// エラーログの設定(初期設定)
$errorLogPath = new \Path("");
$errorLogPath->addArray([dirname(__DIR__, 3), "log", "error", phpversion(), ''], true);
$errLogArray = [];
if (!is_dir($errorLogPath->get())) {
    mkdir($errorLogPath->get());
    $errorLogOldPath = clone $errorLogPath;
    $errorLogOldPath->add("_old");
    mkdir($errorLogOldPath->get());
}
$errorLogPath->setPathEnd();
$errorLogPath->add("php_error.log");
ini_set("error_log", $errorLogPath->get());

$iniPath =new \Path("ini");
$iniPath->setPathEnd();
$iniPath->add("ini.php");
require_once $iniPath->get();

// エラーハンドラ設定
set_error_handler(
    function (
        $error_no,
        $error_msg,
        $error_file,
        $error_line
    ) {
        if (error_reporting() === 0) {
            return;
        }
        throw new ErrorException($error_msg, 0, $error_no, $error_file, $error_line);
    }
);

// register_shutdown_function(function () {
//     $error = error_get_last();

//     // エラーが発生した際にはアラートを出す。(開発環境ではエラー内容も表示)
//     if (!empty($error)) {
//         if (php_sapi_name() !== 'cli') {
//             $cnf = new Header();
//             $errScript = new Public\Important\ScriptClass();

//             $errScript->alert("エラーが発生しました。");
//             if (strcmp($cnf->getVersion(), '-local') === 0 || strcmp($cnf->getVersion(), '-dev') === 0) {
//                 $errMessage = str_replace('\\', '/', $error['message']);
//                 $errMessage = str_replace(array("\r\n", "\r", "\n"), '\\n', $errMessage);
//                 $errMessage = str_replace("'", "\'", $errMessage);
//                 if (strcmp($cnf->getVersion(), '-local') === 0) {
//                     $errFile = str_replace('\\', '/', $error['file']);
//                     $errFile = str_replace('\n', '\\n', $errFile);
//                     $errScript->alert($errMessage. "\\n\\n".
//                         "file: ". $errFile . "\\n".
//                         "line: ". $error['line']);
//                 } else {
//                     $errScript->alert($errMessage);
//                 }
//             }
//         }
//     }
// });

/**
 * sanitize
 *
 * ヌルバイト対策 (POST, GET)
 *
 * @param mixed  $arr
 *
 * @return mixed
 */
function sanitize(mixed $arr = ''): mixed
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
 * 所定のディレクトリまでのディレクトリ群を走査し、パスを生成する。
 *
 * @param string $target
 * @param string $src
 * @@param string $separator
 *
 * @return bool
 */
function createClient(string $target, string $src = '', string $separator = '/'): string
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

    $clientPath = new \Path($clientPath, $separator);
    foreach ($clientAry as $_client) {
        $clientPath->add($_client);
    }

    return $clientPath->get();
}
/**
 * filterInputFix
 * $_SERVER, $_ENVのための、filter_input代替処理。
 *
 *
 * @param  mixed $type
 * @param  mixed $variable_name
 * @param  int $filter
 * @param  mixed|null $options
 *
 * @return mixed|null
 */
function filterInputFix($type, $variable_name, $filter = FILTER_DEFAULT, $options = null): mixed
{
    $checkTypes =[
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
function moldData(mixed $data, string $parameter = ','): mixed
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
 * 出力用の関数。
 *
 * @param mixed $expression
 * @param boolean $formatFlg
 * @param boolean $indentFlg
 * @param boolean $dumpFlg
 * @param array $debug
 *
 * @return void
 */
function output(
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
        $debugValidate = debugValidate($debug, $debugTrace);
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

    return true;
}

/**
 * debugValitate
 *
 * デバッグ出力時のバリデーション。
 *
 * @param array $debug
 * @param array $debugTrace
 *
 * @return array
 */
function debugValidate(array $debug, array $debugTrace): array
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
 * setVendor
 *
 * プラグインのautoloaderを読み込む。
 * *
 * @return void
 */
function setVendor(): void
{
    $allVendorPath = new \PathApplication('plubinDir', VENDOR_DIR);
    $allVendorPath->setAll([
        'requireFile' => $allVendorPath->get(),
    ]);

    $allVendorPath->resetKey('requireFile');
    $allVendorPath->methodPath('AddArray', ["vendor", "autoLoad.php"]);
    $requireFile = $allVendorPath->get();

    if (is_file($requireFile)) {
        require_once $requireFile;
    }
}

/**
 * searchData
 *
 * in_arrayの代替処理。
 * (in_arrayは速度的に問題があるため、issetで対応する)
 *
 * @param  mixed $target
 * @param array $arrayData
 *
 * @return bool
 */
function searchData($target, array $arrayData): bool
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
 * moldImageConfig
 *
 * getImageSize関数で取得した配列を整形する。
 *
 * @param  array|string $imageConfig 画像名(画像パス含む)
 *
 * @return array
 */
function moldImageConfig($imageConfig): array
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
 *画像のサイズを計算する
 *
 * @param string $imageName 画像名(画像パス含む)
 * @param string|int $imageSizeViewValue 画像サイズの表示桁数
 *
 * @return array|false
 */
function calcImageSize(string $imageName, string|int $imageSizeViewValue): array|false
{
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
function emptyValidate(mixed $validate, ?string $word = null): ?bool
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
function checkMemory(): void
{
    static $initialMemoryUse = null;

    if ($initialMemoryUse === null) {
        $initialMemoryUse = memory_get_usage();
    }

    output(number_format(memory_get_usage() - $initialMemoryUse), formatFlg:true);
}
