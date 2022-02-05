<?php
use PublicSetting\Setting as Setting;

define("FALSE_MESSAGE",  "の値が不正です。");
define("NULL_MESSAGE",  "の値を入力してください。");
IncludeDirctories();

class CSV extends CSV_Base {
    private $fileName, $editFlg;

    /**
     * SetHeader
     *
     * ヘッダーのデータをプロパティに取り込む。
     *
     * @param [type] $header
     * @return void
     */
    public function SetHeader($header) {
        $validate = $this->SetCommons($header);
        if ($validate === false) {
            $this->MakeData();
        }
        $this->AddHeader($header);
    }

    /**
     * InputName
     *
     * ファイル名をセット。
     *
     * @return bool
     */
    public function InputName() {
        // postの取得
        $fileName = Setting::GetPost('file-name');

        // ファイル名のバリデート
        $nameValid = $this->ValidateName($fileName);

        if ($nameValid === false) {
            return $nameValid;
        }

        $this->fileName = $fileName;

        if ($nameValid === EXTENSION_NONE_TRUE) {
            $this->fileName .= ".csv";
            $nameValid = true;
        }

        return $nameValid;
    }

    /**
     * ReadData
     *
     * ファイルを読み込み、データをセット。
     *
     * @return void
     */
    public function ReadData() {
        // postの取得
        $key = 'col-number';
        $post = Setting::GetPost($key);
        $data = [$key => $post];

        $valid = $this->ValidateNumber($data);

        $this->editFlg = $valid[$key];

        if ($valid[$key] !== true) {
            return $this->editFlg;
        }

        $this->ReadFile($this->fileName, CSV_PATH);

    }

    /**
     * InputData
     *
     * CSVファイルに記述するデータのセット。
     *
     * @return void
     */
    public function InputData() {
        // postの取得
        $post = Setting::GetPosts();

        // 入力値のバリデート
        $data = [
            'x-value' => $post['x-value'],
            'y-value' => $post['y-value'],
            'z-value' => $post['z-value']
        ];
        $valid = $this->ValidateNumber($data);

        $exitFlg = false;
        foreach ($valid as $_key => $_val) {
            if ($_val === false) {
                $exitFlg = true;
                $this->SetErrorMessage($_key, $_key. FALSE_MESSAGE);
            } else if ($_val === null) {
                $exitFlg = true;
                $this->SetErrorMessage($_key, $_key . NULL_MESSAGE);

            }
        }
        // 不正値が1つでもあればデータはセットしない
        if (!$exitFlg) {
            // データセット
            $this->SetData($data);
        } else {
            return false;
        }
    }

    private function SetErrorMessage($key, $message = '') {
        $elm = ["<div class='warning'>", "</div>"];

        if (!$message) {
            $message = $key. "の値が不正です。";
        }

        print_r($elm[0] . $message . $elm[1]);
    }

    /**
     * SetData
     *
     * データをセット
     *
     * @param [type] $data
     * @return void
     */
    private function SetData($data) {
        $validate = $this->SetCommons($data);
        if ($validate === false) {
            return -1;
        } else {
            $validate = $this->CountValidate($data);
            if ($this->editFlg === true) {
                $col = Setting::GetPost('col-number');
                $this->EditData($col, $data);
            } else if ($this->editFlg === null && $validate === true) {
                $this->AddData($data);
            }
        }
    }

    /**
     * SetCSV
     *
     *
     * 指定したCSVファイルに、設定済みのデータを書き込む。
     *
     * @return void
     */
    public function SetCSV() {
        // データをCSVファイルに書き込み
        // 存在しない場合は、CSVファイルを作成
        $this->MakeFile($this->fileName, CSV_PATH);
    }

    /**
     * Output
     *
     *
     * 指定したCSVファイルを読み込み、配列用データに成形して返す。
     *
     * @return array
     */

    public function OutData($option=null) {
        if (!is_file(CSV_PATH. $this->fileName) || $this->ValidateName($this->fileName) === false) {
            $ret = false;
        } else {
            $this->ReadFile($this->fileName);
            $ret = $this->OutData($option);
        }
        return $ret;
    }
}
