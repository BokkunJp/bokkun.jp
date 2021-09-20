<?php
// クラス化
class Setting {
    protected $authorities;

    function __construct() {
        $this->Initialize();
    }

    protected function Initialize() {
        unset($this->authorities);
        $this->authorities = array();
    }

    protected function DenyAuthoritys($authorities) {
        if (!is_array($authorities)) {
            trigger_error("引数が不正です。", E_USER_ERROR);
        }
        foreach ($authorities as $value) {
            $this->authorities[] = $value;
        }
    }

    protected function DenyAuthority($authority) {
        $this->DenyAuthoritys([$authority]);
    }

    protected function AllowAuthoritys($authority) {
        $key = array_keys($this->authorities, $authority);
        $this->authorities = array_splice($this->authorities, $key, 1);
    }

    protected function AllowAuthority($authority) {
        $key = array_keys($this->authorities, $authority);
        $this->authorities = array_splice($this->authorities, $key, 1);
    }

    public function SetDefault($authority) {
        $this->Initialize();
        $this->DenyAuthoritys($authority);
    }


    public function ViewAuthority($authorityName=null) {
        if (!isset($authorityName)) {
            foreach ($this->authorities as $value) {
                var_dump("$value is true.");
            }
        } else {
            var_dump("$authorityName is true.");
        }
    }

    // タグ名リスト生成
    public function CreateAuthorityList($notuseList) {
        $select = '<select>';
        $authorityList = $this->authorities;
        $notuse = array_search('script', $authorityList);
        if (isset($notuse)) {
            foreach ($notuseList as $notuse) {
                $keys = array_keys($authorityList, $notuse);
                foreach ($keys as $key) {
                    unset($authorityList[$key]);            // 不要なタグの削除
                }
            }
        }
        foreach ($authorityList as $value) {
            $select .= "<option>$value</option>";
        }
        $select .= '</select>';

        return $select;
    }


}
