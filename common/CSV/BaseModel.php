<?php

namespace Common;

define('CSV_PATH', AddPath(PUBLIC_CSV_DIR, '', false, '/') . AddPath(basename(getcwd()), '', false, '/'));
define("EXTENSION_NONE_TRUE", 2);
class CSV_Base
{
    private $data;
    private $tmp;

    protected function MakeData()
    {
        $this->data = [];
    }

    protected function AddHeader($header)
    {
        $this->data[0] = $header;
    }

    protected function GetHeader()
    {
        return $this->data[0];
    }

    protected function AddData($inData)
    {
        $this->data[] = $inData;
    }

    protected function EditData($col, $inData)
    {
        if (is_numeric($col) && $col >= 1) {
            $this->data[$col] = $inData;
        } else {
            user_error("不正な値が入力されました。");
        }
    }

    protected function InputCommonValidate()
    {
        $ret = true;
        if (!isset($this->tmp) || is_null($this->tmp)) {
            $ret = false;
        }

        if (!is_array($this->tmp)) {
            $ret = false;
        }
        return $ret;
    }

    protected function SetCommons($data)
    {
        $this->tmp = $data;
        $validate = $this->InputCommonValidate();
        $this->tmp = null;

        return $validate;
    }

    protected function CountValidate($data)
    {
        return count($this->GetHeader()) === count($data) ? true : false;
    }

    public function ViewData($type = '')
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
     * ReadFile
     * ファイル読み込み
     *
     * @param [String] $fileName
     * @param [String] $filePath
     * @return void
     */
    protected function ReadFile($fileName, $filePath = CSV_PATH)
    {
        // ファイルパスにCSVファイルが存在しない場合は終了
        if (!file_exists(AddPath($filePath, $fileName, false))) {
            return false;
        }

        $this->data = null; // データリセット
        $fileHandler = fopen(AddPath($filePath, $fileName, false), "r");
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
     * MoldCsv
     * CSVデータを配列用に成形
     *
     * @return boolean|array
     */
    protected function MoldCsv($option)
    {
        if (!isset($this->data) || !is_array($this->data)) {
            return false;
        }
        $header = $this->GetHeader();
        $row = $this->data;
        unset($row[0]);
        $ret = null;
        if ($option === 'header') {
            $ret = $header;
        } elseif ($option === 'body' || $option === 'row') {
            $ret = $row;
        }

        if (is_null($ret)) {
            $ret = [];
            foreach ($row as $r_key => $r_data) {
                foreach ($r_data as $col_key => $col_data) {
                    $ret[$r_key][$header[$col_key]] = $col_data;
                }
            }
        }

        return $ret;
    }


    /**
     * ValidateName
     * 名称チェック
     *
     * @param string $haystack
     * @param string $extensiton
     * @return void
     */
    protected function ValidateName($haystack, $extensiton = 'csv')
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
     * MakeFile
     * ファイル作成
     *
     * @param string $fileName
     * @param string $filePath
     * @return void
     */
    protected function MakeFile($fileName, $filePath = CSV_PATH)
    {

        // CSV保管用のディレクトリがない場合は作成
        if (!file_exists($filePath)) {
            mkdir($filePath);
        }


        $fileHandler = @fopen(AddPath($filePath, $fileName, false, '/'), "w");

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
     * ValidateNumber
     * 入力値が数字かどうかチェックする。
     * (配列が入力された場合は各要素について同様にチェックする)
     *
     * @param [type] $data
     * @return array|boolean
     */
    protected function ValidateNumber($data)
    {
        if (is_array($data)) {
            $ret = [];

            foreach ($data as $_key => $_val) {
                $ret[$_key] = $this->ValidateNumber($_val);
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
