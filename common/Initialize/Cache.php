<?php
use Common\Session;


class Cache {

    private string $key;
    private ?array $cache;
    private array $data;
    private ?Session $session;

    function __construct(private string $id)
    {
        $this->connect();
        $this->cache = [];
    }

    private function connect(): void
    {
        $this->session = new Session();

        if ($this->session->read('cache')) {
            $this->session->write('cache', []);
        }
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function set($value): void
    {
        $this->cache[$this->key] = $value;
    }

    public function get(string $key = null): mixed
    {
        if (!$key) {
            $key = $this->key;
        }
        return $this->cache[$key];
    }

    public function save(): void
    {
        $this->session->writeArray('cache', $this->id, $this->cache);
    }

    public function load(): mixed
    {
        $tmp = $this->session->read('cache');

        if (!is_null($tmp)) {
            $this->cache = $tmp;
        }
    }
}
