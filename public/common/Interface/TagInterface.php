<?php
namespace Tag;

interface BaseInterface
{
    protected function Initialize($init);
    protected function AllowAuthoritys($authority);
    protected function DenyAuthority($authority);
    public function SetDefault();
    public function ViewAuthority($authorityName);
    public function CreateAuthorityList($notuseList);
}
