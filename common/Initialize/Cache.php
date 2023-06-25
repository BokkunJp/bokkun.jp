<?php
use Common\Session;


class Cache {

    private string $key;
    private ?array $cache;
    private array $data;
    private ?Session $session;

    function __construct(private string $id)
    {
        $this->SessionConnect();
        $this->cache = [];
    }

    private function SessionConnect(): void
    {
        $this->session = new Session();

        if ($this->session->Read('cache')) {
            $this->session->Write('cache', []);
        }
    }

    public function SetKey(string $key): void
    {
        $this->key = $key;
    }

    public function Set($value): void
    {
        $this->cache[$this->key] = $value;
    }

    public function Get(string $key = null): mixed
    {
        if (!$key) {
            $key = $this->key;
        }
        return $this->cache[$key];
    }

    public function Save(): void
    {
        $this->session->WriteArray('cache', $this->id, $$this->cache);
    }

    public function Load(): mixed
    {
        $tmp = $this->session->Read('cache');

        if (!is_null($tmp)) {
            $this->cache = $tmp;
        }
    }
}
