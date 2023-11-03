<?php

abstract class AbstractBase
{
    abstract protected function getValue();
    abstract protected function Layout();
    abstract protected function init();
    public function main()
    {
        init();   // 初期処理を記述
    }
}
