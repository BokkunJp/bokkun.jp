<?php
define('CSV', AddPath(PUBLIC_CSV_DIR, '', false, '/') . AddPath(basename(getcwd()), '', false, '/'));
class CSV_Base {

    private $data, $tmp;

    protected function MakeData() {
        $this->data = [];
    }

    protected function AddHeader($header) {
        $this->data[0] = $header;
    }

    protected function GetHeader() {
        return $this->data[0];
    }

    protected function AddData($data) {
        $this->data[] = $data;
    }

    protected function InputCommonValidate() {
        if (!isset($this->tmp) || is_null($this->tmp)) {
            return false;
        }

        if (!is_array($this->tmp)) {
            return false;
        }
        return true;
    }

    protected function SetCommons($data) {
        $this->tmp = $data;
        $validate = $this->InputCommonValidate();
        $this->tmp = null;

        return $validate;
    }

    protected function CountValidate($data) {
        return count($this->GetHeader()) === count($data) ? true : false;
    }

    public function ViewData($type = '') {
        if ($type === 'all') {
            print_r($this->data);
        } else {
            foreach ($this->data as $_index => $_value) {
                print_r("data[{$_index}] is ");
                print_r($_value);
            }
        }
    }

    private function NameValidate($haystack, $extensiton = 'csv') {
        $ret = true;

        // 文字列以外は除外 (配列など)
        if (!is_string($haystack)) {
            $ret = false;
        }

        // 「_」で始まるファイル名は除外 (削除用)
        if (preg_match('/^_.*$/', $haystack)) {
            $ret = false;
        }

        // a-z, A-Z, 0-9, 日本語のみをファイル名に用いることができるとする (特殊文字は不可)
        mb_regex_encoding('UTF-8');
        if (!preg_match("/[a-zA-Z0-9-_-ぁ-んァ-ヶー一-龠]+\.{$extensiton}$/", $haystack)) {
            $ret = false;
        }

        return $ret;
    }

    public function MakeFile($fileName, $filePath = CSV) {

        // CSV保管用のディレクトリがない場合は作成
        if (!file_exists($filePath)) {
            mkdir($filePath);
        }

        $validate = $this->NameValidate($fileName);
        if ($validate === false) {
            return false;
        }
        $fileHandler = fopen(AddPath($filePath, $fileName, false), "w");
        if ($fileHandler) {
            foreach ($this->data as $_data) {
            if (fputcsv($fileHandler, $_data) === false) {
                user_error("書き込みに失敗しました。", E_RECOVERABLE_ERROR);
            }
            }
            fclose($fileHandler);
        }
    }

}
