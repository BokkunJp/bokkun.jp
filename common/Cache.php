<?php
namespace Common\Important;

use Common\Important\Session;

class Cache {

    private string $key;
    private ?array $cache;
    private $current;

    function __construct(private string $id)
    {
        $this->cache = [];
    }

    private function connect(Session $session): void
    {
        if ($session->read('cache')) {
            $session->write('cache', []);
        }
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function set($value): bool
    {
        $result = true;
        if (empty($this->key)) {
            $result = false;
        } else {
            $this->cache[$this->key] = $value;
            $this->current = $value;
        }

        return $result;
    }

    public function get(string $key = null): mixed
    {
        if (!$key) {
            $key = $this->key;

            return $this->cache[$key];
        } else {

            return false;
        }
    }

    public function getCurrent()
    {
        return $this->current;
    }

    public function save(Session $session): void
    {
        $session->writeArray('cache', $this->id, $this->cache);
    }

    public function load($session): void
    {
        $tmp = $session->read('cache');

        if (!is_null($tmp)) {
            $this->cache = $tmp;
        }
    }
}
