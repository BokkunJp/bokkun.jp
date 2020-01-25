<?php
IncludeDirctories();

class CSV extends CSV_Base {

    public function SetHeader($header) {
        $validate = $this->SetCommons($header);
        if ($validate === false) {
            $this->MakeData();
        }
        $this->AddHeader($header);
    }

    public function SetData($data) {
        $validate = $this->SetCommons($data);
        if ($validate === false) {
            return -1;
        } else {
            $validate = $this->CountValidate($data);
            if ($validate === true) {
                $this->AddData($data);
            }
        }
    }
}
