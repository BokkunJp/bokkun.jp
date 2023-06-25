<?php
trait Component
{
    public $data;
    public function Getter() {
        return $this->data;
    }
    public function Setter($data) {
        $this->data = $data;
    }
}