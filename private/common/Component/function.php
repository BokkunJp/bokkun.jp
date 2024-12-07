<?php

// クラス化
class Setting
{
    protected $authorities;

    public function __construct()
    {
        $this->initialize();
    }

    protected function initialize()
    {
        unset($this->authorities);
        $this->authorities = array();
    }

    protected function denyAuthoritys($authorities)
    {
        if (!is_array($authorities)) {
            throw new Error("引数が不正です。");
        }
        foreach ($authorities as $value) {
            $this->authorities[] = $value;
        }
    }

    protected function denyAuthority($authority)
    {
        $this->denyAuthoritys([$authority]);
    }

    protected function allowAuthoritys($authority)
    {
        $key = array_keys($this->authorities, $authority);
        $this->authorities = array_splice($this->authorities, $key, 1);
    }

    protected function allowAuthority($authority)
    {
        $key = array_keys($this->authorities, $authority);
        $this->authorities = array_splice($this->authorities, $key, 1);
    }

    public function setDefault($authority)
    {
        $this->initialize();
        $this->denyAuthoritys($authority);
    }


    public function viewAuthority($authorityName=null)
    {
        if (!isset($authorityName)) {
            foreach ($this->authorities as $value) {
                var_dump("$value is true.");
            }
        } else {
            var_dump("$authorityName is true.");
        }
    }

    // タグ名リスト生成
    public function createAuthorityList($notuseList)
    {
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
