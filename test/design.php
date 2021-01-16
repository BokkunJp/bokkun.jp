<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
Output(['a' => 1, 2 => 2], true);
$propaty = new Objects();
Output($propaty, true);
$propaty->Inport(2);
Output($propaty, true);
$propaty->Save();
$propaty->Inport(23);
Output($propaty, true);


class Objects {
    private $buf, $tmp;
    function __construct() {
        $this->Clearn();
    }

    public function Inport($val) {
        $this->tmp = $val;
    }

    public function Clearn() {
        unset($this->tmp);
        $this->tmp = null;
    }

    public function Save() {
        $this->buf = $this->tmp;
    }

    public function Export() {

        $sess = new PublicSetting\Session();
        if ($this->buf) {
            $sess->Write('buf', $this->buf);
        }
    }

    function __destruct() {
        $this->Clearn();
    }
}