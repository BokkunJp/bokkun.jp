<?php

class ArrayClass
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function findValue(string $key): mixed
    {
        $ret = null;
        if ($this->isKey($key)) {
            $ret = $this->data[$key];
        }

        return $ret;
    }

    public function iskey(string $key): bool
    {
        $ret = false;
        if (isset($this->data[$key])) {
            $ret = true;
        }

        return $ret;
    }

    public function push($data)
    {
        $this->data[] = $data;
    }
    public function pull()
    {
        $value = end($this->data);
        $key = array_key_last($this->data);
        unset($this->data[$key]);

        return $value;
    }

    public function export()
    {
        return (array)$this->data;
    }

    public function convertObjectClass()
    {
        return new ObjectClass($this->data);
    }

    public function debug()
    {
        debug($this->data);
    }
}
