<?php
$csvPath = new \Path(PUBLIC_DIR_LIST['csv']);
$cwdPath = new \Path(getcwd());
$csvPath->add(basename($cwdPath->get()));
define('CSV_PATH', $csvPath->get());
define("EXTENSION_NONE_TRUE", 2);
class productCSV_Base
{
    private $data;
    private $tmp;

    use CommonTrait;

    protected function makeData()
    {
        $this->data = [];
    }

    protected function addHeader($header)
    {
        $this->data[0] = $header;
    }

    protected function getHeader()
    {
        return $this->data[0];
    }

    protected function addData($inData)
    {
        $this->data[] = $inData;
    }

    protected function editData($col, $inData)
    {
        if (is_numeric($col) && $col >= 1) {
            $this->data[$col] = $inData;
        } else {
            user_error("不正な値が入力されました。");
        }
    }

    protected function inputCommonValidate()
    {
        if (!isset($this->tmp) || is_null($this->tmp)) {
            return false;
        }

        if (!is_array($this->tmp)) {
            return false;
        }
        return true;
    }

    protected function setCommons($data)
    {
        $this->tmp = $data;
        $validate = $this->inputCommonValidate();
        $this->tmp = null;

        return $validate;
    }

    protected function countValidate($data)
    {
        return count($this->getHeader()) === count($data) ? true : false;
    }

    public function viewData($type = '')
    {
        if ($type === 'all') {
            print_r($this->data);
        } else {
            foreach ($this->data as $_index => $_value) {
                print_r("data[{$_index}] is ");
                print_r($_value);
            }
        }
    }

    /**
     * readFile
     * ファイル読み込み
     *
     * @param [String] $fileName
     * @param [String] $filePath
     * @return void
     */
    protected function readFile($fileName, $filePath = CSV_PATH)
    {
        // ファイルパスにCSVファイルが存在しない場合は終了
        $pathSet = new \Path($filePath);
        $pathSet->setPathEnd();
        $path = $pathSet->add($fileName, false);
        if (!file_exists($path)) {
            return false;
        }

        $this->data = null; // データリセット
        $fileHandler = fopen($path, "r");
        if ($fileHandler) {
            while ($_data = fgetcsv($fileHandler)) {
                if ($_data === false) {
                    user_error("読み込みに失敗しました。", E_RECOVERABLE_ERROR);
                } else {
                    $this->data[] = $_data;
                }
            }
            fclose($fileHandler);
        }
    }

    /**
     * moldCsv
     * CSVデータを配列用に成形
     *
     * @return boolean|array
     */
    protected function moldCsv($option)
    {
        if (!isset($this->data) || !is_array($this->data)) {
            return false;
        }
        $header = $this->getHeader();
        $row = $this->data;
        unset($row[0]);
        if ($option === 'header') {
            return $header;
        } elseif ($option === 'body' || $option === 'row') {
            return $row;
        }

        $ret = [];
        foreach ($row as $r_key => $r_data) {
            foreach ($r_data as $col_key => $col_data) {
                $ret[$r_key][$header[$col_key]] = $col_data;
            }
        }

        return $ret;
    }


    /**
     * validateName
     * 名称チェック
     *
     * @param string $haystack
     * @param string $extensiton
     * @return void
     */
    protected function validateName($haystack, $extensiton = 'csv')
    {
        $ret = true;

        // 行頭・行末の空白を取り除く
        $haystack = trim($haystack);

        // 文字列以外は除外 (配列など)
        if (!is_string($haystack)) {
            $ret = false;
        }

        // 空文字は除外
        if (empty($haystack)) {
            $ret = false;
        }

        // 「_」で始まるファイル名は除外 (削除用)
        if (preg_match('/^_.*$/', $haystack)) {
            $ret = false;
        }

        // a-z, A-Z, 0-9, 日本語のみをファイル名に用いることができるとする (特殊文字は不可)
        mb_regex_encoding('UTF-8');

        if (preg_match("/[a-zA-Z0-9-_-ぁ-んァ-ヶー一-龠]$/", $haystack)) {
            if (!preg_match("/[a-zA-Z0-9-_-ぁ-んァ-ヶー一-龠]+\.{$extensiton}$/", $haystack)) {
                $ret = EXTENSION_NONE_TRUE;
            } else {
                $ret = true;
            }
        }

        return $ret;
    }

    /**
     * makeFile
     * ファイル作成
     *
     * @param string $fileName
     * @param string $filePath
     * @return void
     */
    protected function makeFile($fileName, $filePath = CSV_PATH)
    {

        // CSV保管用のディレクトリがない場合は作成
        if (!file_exists($filePath)) {
            mkdir($filePath);
        }
        $pathSet = new \Path($filePath);
        $pathSet->setPathEnd();
        $path = $pathSet->add($fileName, false);


        $fileHandler = @fopen($path, "w");

        // ファイルの読み込みに失敗した場合は中断
        if ($fileHandler === false) {
            user_error("ファイルの展開に失敗しました。", E_USER_ERROR);
            exit;
        }
        if ($fileHandler) {
            foreach ($this->data as $_data) {
                if (fputcsv($fileHandler, $_data) === false) {
                    user_error("ファイルの書き込みに失敗しました。", E_USER_ERROR);
                    exit;
                }
            }
            fclose($fileHandler);
        }
    }

    /**
     * validateNumber
     * 入力値が数字かどうかチェックする。
     * (配列が入力された場合は各要素について同様にチェックする)
     *
     * @param [type] $data
     * @return array|boolean
     */
    protected function validateNumber($data)
    {
        if (is_array($data)) {
            $ret = [];

            foreach ($data as $_key => $_val) {
                $ret[$_key] = $this->validateNumber($_val);
            }
        } else {
            $ret = true;

            if ($data === '') {
                $ret = null;
            } elseif (!is_numeric($data)) {
                $ret = false;
            }
        }
        return $ret;
    }
}
