<?php

trait Component
{
    public $data;
    public function getter()
    {
        return $this->data;
    }
    public function setter($data)
    {
        $this->data = $data;
    }
}
