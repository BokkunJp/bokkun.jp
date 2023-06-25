<?php
class ObjectClass {
    private stdClass $data;

    function __construct(array $data)
    {
        $this->data = (object)$data;
    }

    public function iskey(string $key): bool
    {
        $ret = false;
        if (isset($this->data->$key)) {
            $ret = true;
        }

        return $ret;
    }

    public function findValue(string $key)
    {
        $ret = false;
        if ($this->isKey($key)) {
            $ret = $this->data->$key;
        }

        return $ret;
    }

    public function convertArrayClass()
    {
        return new ArrayClass((array)$this->data);
    }
}
