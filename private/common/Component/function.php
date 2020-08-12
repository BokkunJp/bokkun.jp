<?php
// クラス化
class Setting {
    protected $authoritys;
    
    function __construct() {
        $this->Initialize();
    }

    protected function Initialize() {
        unset($this->authoritys);
        $this->authoritys = array();
    }
    
    protected function DenyAuthoritys($authoritys) {
        if (!is_array($authoritys)) {
            trigger_error("引数が不正です。", E_USER_ERROR);
        }
        foreach ($authoritys as $value) {
            $this->authoritys[] = $value;
        }
    }

    protected function DenyAuthority($authority) {
        $this->DenyAuthoritys([$authority]);
    }

    protected function AllowAuthoritys($authority) {
        $key = array_keys($this->authoritys, $authority);
        $this->authoritys = array_splice($this->authoritys, $key, 1);
    }
    
    protected function AllowAuthority($authority) {
        $key = array_keys($this->authoritys, $authority);
        $this->authoritys = array_splice($this->authoritys, $key, 1);
    }
    
    public function SetDefault($authority) {
        $this->Initialize();
        $this->DenyAuthoritys($authority);
    }
    
    
    public function ViewAuthority($authorityName=null) {
        if (!isset($authorityName)) {
            foreach ($this->authoritys as $value) {
                var_dump("$value is true.");
            }
        } else {
            var_dump("$authorityName is true.");
        }
    }
    
    // タグ名リスト生成
    public function CreateAuthorityList($notuseList) {
        $select = '<select>';
        $authorityList = $this->authoritys;
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
