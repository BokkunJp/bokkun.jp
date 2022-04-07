<?php

// セッションスタート
if (!isset($_SESSION)) {
    session_start();
} else {
    session_reset();
}

// タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');

// エラーログの設定(初期設定)
$errLogArray = [];
$errLogArray['errLogBasePath'] = AddPath(dirname(__DIR__, 3), AddPath("log", "error"), false);
$errLogArray['errLogPath'] = AddPath($errLogArray['errLogBasePath'], phpversion());
if (!is_dir($errLogArray['errLogPath'])) {
    mkdir($errLogArray['errLogPath']);
    mkdir(AddPath($errLogArray['errLogPath'], '_old'));
}
ini_set("error_log", AddPath($errLogArray['errLogPath'], "php_error.log", false));
unset($errLogArray);

require_once AddPath('mode', 'remote.php', false);

// エラーハンドラ設定
set_error_handler(function ($error_no, $error_msg, $error_file, $error_line) {
    if (error_reporting() === 0) {
        return;
    }
    throw new ErrorException($error_msg, 0, $error_no, $error_file, $error_line);
});

register_shutdown_function(function () {
    $error = error_get_last();

    // エラーが発生した際にはアラートを出す。(開発環境ではエラー内容も表示)
    if (!empty($error)) {
        if (php_sapi_name() !== 'cli') {
            $cnf = new Header();
            $errScript = new BasicTag\ScriptClass();

            $errScript->Alert("エラーが発生しました。");
            if (strcmp($cnf->GetVersion(), '-local') === 0 || strcmp($cnf->GetVersion(), '-dev') === 0) {
                $errMessage = str_replace('\\', '/', $error['message']);
                $errMessage = str_replace(array("\r\n", "\r", "\n"), '\\n', $errMessage);
                $errMessage = str_replace("'", "\'", $errMessage);
                if (strcmp($cnf->GetVersion(), '-local') === 0) {
                    $errFile = str_replace('\\', '/', $error['file']);
                    $errFile = str_replace('\n', '\\n', $errFile);
                    $errScript->Alert($errMessage. "\\n\\n".
                        "file: ". $errFile . "\\n".
                        "line: ". $error['line']);
                } else {
                    $errScript->Alert($errMessage);
                }
            }
        }
    }
});

/**
 * AddPath
 *
 * 既存のパスに新たな要素を追加する
 *
 * @param string $local
 * @param string $addpath
 * @param boolean $lastSeparator
 * @param string $separator
 *
 * @return string
 */
function AddPath($local, $addpath, $lastSeparator=true, $separator=DIRECTORY_SEPARATOR)
{
    if (mb_substr($local, -1) == $separator) {
        $first = '';
    } else {
        $first = $separator;
    }
    if ($lastSeparator == true) {
        $last = $separator;
    } else {
        $last = '';
    }

    $local .= $first. $addpath. $last;    // パス追加 + パス結合

    $local = htmlspecialchars($local);    // XSS対策

    return $local;
}

/**
 * Sanitize
 *
 * ヌルバイト対策 (POST, GET)
 *
 * @param mixed $arr
 *
 * @return mixed
 */
function Sanitize($arr): mixed
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
function CreateClient($target, $src = ''): string
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
function CheckSession($SessionName, $chkFlg): bool
{
    $input = CommonSetting\Setting::GetPost($SessionName);
    $session = new CommonSetting\Session();
    $ret = true;

    if ($chkFlg === true) {
        echo 'デバッグ用<br/>';
        echo 'post: ' . $input . '<br/>';
        echo 'session: ' . $session->Read($SessionName) . '<br/><br/>';
    }

    if (is_null($input) || $input === false || is_null($session->Read($SessionName)) || !hash_equals($session->Read($SessionName), $input)) {
        $ret = false;
    }

    return $ret;
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
 * @return mixed|null
 */
function filter_input_fix($type, $variable_name, $filter = FILTER_DEFAULT, $options = null): mixed
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

    if (SearchData($type, $checkTypes) || filter_has_var($type, $variable_name)) {
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
 * MoldData
 *
 * @param [mixed] $data
 * @param string $parameter
 *
 * @return mixed
 */
function MoldData($data, $parameter = ','): mixed
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
 * Output
 *
 * @param [mixed] $expression
 * @param boolean $formatFlg
 * @param boolean $indentFlg
 * @param array $debug
 *
 * @return void
 */
function Output($expression, $formatFlg = false, $indentFlg = true, array $debug = [])
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
        $debugValidate = DebugValidate($debug, $debugTrace);
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

/**
 * DebugValitate
 *
 * @param array $debug
 * @param array $debugTrace
 *
 * @return array
 */
function DebugValidate(array $debug, array $debugTrace): array
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

function SetPlugin($name)
{
    if (is_array($name)) {
        foreach ($name as $_dir) {
            SetPlugin($_dir);
        }
        return FINISH;
    }

    if (is_dir(AddPath(PLUGIN_DIR, $name))) {
        IncludeDirctories(AddPath(PLUGIN_DIR, $name));
    }
}

/**
 * SetPlugin
 *
 * @return void
 */
function SetAllPlugin()
{
    $addDir = scandir(PLUGIN_DIR);

    foreach ($addDir as $_key => $_dir) {
        if (strpos($_dir, '.') !== false || strpos($_dir, '..')  !== false) {
            unset($addDir[$_key]);
        }
    }

    SetPlugin($addDir);
}

/**
 * SearchData
 *
 * in_arrayの代替処理。
 * (in_arrayは速度的に問題があるため、issetで対応する)
 *
 * @param  mixed $target
 * @param array $arrayData
 *
 * @return bool
 */
function SearchData($target, array $arrayData): bool
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
 * getimagesizeで取得した配列を整形する。
 *
 * @param  array|string $imageName 画像名(画像パス含む)
 *
 * @return array
 */
function MoldImageConfig($imageConfig): array
{
    $ret = [];
    if (!is_array($imageConfig)) {
        $ret[] = [];
    } else {
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
 * CalcImageSize
 *画像のサイズを計算する
 *
 * @param string $imageName 画像名(画像パス含む)
 * @param string $imageSizeViewValue 画像サイズの表示桁数
 *
 * @return array
 */
function CalcImageSize($imageName, $imageSizeViewValue): array
{
    if (is_array($imageName)) {
        $ret = false;
    } else {
        if (!file_exists($imageName) || !FileExif($imageName)) {
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
            }
        }

        $ret = ['size' => $imageSize, 'sizeUnit' => $imageSizeUnit];

        $ret = array_merge(MoldImageConfig($imageConfig), $ret);
    }

    return $ret;
}

/**
 * CalcImageSize
 *画像のサイズを計算する
 *
 * @param string $imageName 画像名(画像パス含む)
 *
 * @return array
 */
function CalcAllImageSize($imageName): array
{
    if (!is_string($imageName)) {
        $ret = false;
    } else {
        $imageConfig = getimagesize($imageName);
        $imageSize = filesize($imageName);
        $imageSizeUnitArray = ['K', 'M', 'G', 'T', 'P'];

        $imageSizeUnit = '';
        foreach ($imageSizeUnitArray as $_imageSizeUnit) {
            if ($imageSize >= IMAGE_MAX_VALUE) {
                $imageSize = $imageSize / IMAGE_MAX_VALUE;
                $imageSizeUnit = $_imageSizeUnit;
            }
        }

        $ret = ['size' => $imageSize, 'sizeUnit' => $imageSizeUnit];

        $ret = array_merge(MoldImageConfig($imageConfig), $ret);
    }

    return $ret;
}

/**
 * EmptyValidate
 *
 * @param mixed $validate
 * @param string|null $word
 * @return boolean|null
 */
function EmptyValidate($validate, ?string $word = null): ?bool
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
