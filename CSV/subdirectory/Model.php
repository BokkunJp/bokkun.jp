<?php

use Public\Important\Setting as Setting;

define("FALSE_MESSAGE", "の値が不正です。");
define("NULL_MESSAGE", "の値を入力してください。");
// includeDirctories();

class CSV1 extends CSV1_Base
{
    private $fileName;
    private $editFlg;

    /**
     * SetHeader
     *
     * ヘッダーのデータをプロパティに取り込む。
     *
     * @param [type] $header
     * @return void
     */
    public function setHeader($header)
    {
        $validate = $this->setCommons($header);
        if ($validate === false) {
            $this->makeData();
        }
        $this->addHeader($header);
    }

    /**
     * inputName
     *
     * ファイル名をセット。
     *
     * @return bool
     */
    public function inputName()
    {
        // postの取得
        $fileName = Setting::getPost('file-name');

        // ファイル名のバリデート
        $nameValid = $this->validateName($fileName);

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
     * readData
     *
     * ファイルを読み込み、データをセット。
     *
     * @return void
     */
    public function readData()
    {
        // postの取得
        $key = 'col-number';
        $post = Setting::getPost($key);
        $data = [$key => $post];

        $valid = $this->validateNumber($data);

        $this->editFlg = $valid[$key];

        if ($valid[$key] !== true) {
            return $this->editFlg;
        }

        $this->readFile($this->fileName, CSV_PATH);
    }

    /**
     * inputData
     *
     * CSVファイルに記述するデータのセット。
     *
     * @return void
     */
    public function inputData()
    {
        // postの取得
        $post = Setting::getPosts();

        // 入力値のバリデート
        $data = [
            'x-value' => $post['x-value'],
            'y-value' => $post['y-value'],
            'z-value' => $post['z-value']
        ];
        $valid = $this->validateNumber($data);

        $exitFlg = false;
        foreach ($valid as $_key => $_val) {
            if ($_val === false) {
                $exitFlg = true;
                $this->setErrorMessage($_key, $_key. FALSE_MESSAGE);
            } elseif ($_val === null) {
                $exitFlg = true;
                $this->setErrorMessage($_key, $_key . NULL_MESSAGE);
            }
        }
        // 不正値が1つでもあればデータはセットしない
        if (!$exitFlg) {
            // データセット
            $this->setData($data);
        } else {
            return false;
        }
    }

    private function setErrorMessage($key, $message = '')
    {
        $elm = ["<div class='warning'>", "</div>"];

        if (!$message) {
            $message = $key. "の値が不正です。";
        }

        print_r($elm[0] . $message . $elm[1]);
    }

    /**
     * setData
     *
     * データをセット
     *
     * @param [type] $data
     * @return void
     */
    private function setData($data)
    {
        $validate = $this->setCommons($data);
        if ($validate === false) {
            return -1;
        } else {
            $validate = $this->countValidate($data);
            if ($this->editFlg === true) {
                $col = Setting::getPost('col-number');
                $this->editData($col, $data);
            } elseif ($this->editFlg === null && $validate === true) {
                $this->addData($data);
            }
        }
    }

    /**
     * setCsv
     *
     *
     * 指定したCSVファイルに、設定済みのデータを書き込む。
     *
     * @return void
     */
    public function setCsv()
    {
        // データをCSVファイルに書き込み
        // 存在しない場合は、CSVファイルを作成
        $this->makeFile($this->fileName, CSV_PATH);
    }

    /**
     * outData
     *
     *
     * 指定したCSVファイルを読み込み、配列用データに成形して返す。
     *
     * @return array
     */

    public function outData($option = null)
    {
        if (!is_file(CSV_PATH. $this->fileName) || $this->validateName($this->fileName) === false) {
            $ret = false;
        } else {
            $this->readFile($this->fileName);
            $ret = $this->moldCsv($option);
        }
        return $ret;
    }
}
