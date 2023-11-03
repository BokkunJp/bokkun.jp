<?php

namespace Interface\Important;

interface BaseInterface
{
    protected function initialize($init);
    protected function allowAuthoritys($authority);
    protected function denyAuthority($authority);
    public function setDefault();
    public function viewAuthority($authorityName);
    public function createAuthorityList($notuseList);
}
