<?php
/**
 * PathApplicationクラス
 *
 * 複数のPathクラスをまとめて扱うためのクラス。
 */
class PathApplication {
    private ?string $key;
    private array $data;
    private string|\Path $tmp;
    private string $sepalator;

    function __construct(string $firstKey, string $path, string $sepalator = '/')
    {
        $this->key = $firstKey;
        $this->data[$this->key] = new \Path($path, $sepalator);
        $this->tmp = $this->data[$this->key];
        $this->sepalator = $sepalator;
    }

    public function set(string $key, string|\Path $data = ''): void
    {
        $this->key = $key;
        if (empty($data) && !empty($this->tmp)) {
            $data = $this->tmp;
        }

        if ($data instanceof \Path) {
            $this->data[$this->key] = clone $data;
        } else {
            $this->data[$this->key] = new \Path($data, $this->sepalator);
        }

        $this->tmp = $data;
    }

    public function setAll(array $dataSet)
    {
        foreach ($dataSet as $key => $data) {
            $this->set($key, $data);
        }
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function resetKey(): void
    {
        $this->key = null;
    }

    public function methodPath(string $methodName, string|array|bool ...$element): void
    {
        $count = count($element);
        if (method_exists($this->data[$this->key], $methodName)) {
            if (is_array($element) && !empty($element)) {
                if ($count === 3) {
                    $this->data[$this->key]->$methodName($element[0], $element[1], $element[2]);
                } else if ($count === 2) {
                    $this->data[$this->key]->$methodName($element[0], $element[1]);
                } else if ($count === 1) {
                    $this->data[$this->key]->$methodName($element[0]);
                }
            } else {
                $this->data[$this->key]->$methodName();
            }
        }
    }

    public function get(): string|array
    {
        $result = null;
        if (!is_null($this->key)) {
            if (isset($this->data[$this->key])) {
                $result = $this->data[$this->key]->get();
            }
        } else {
            $result = [];

            foreach ($this->data as $key => $value) {
                $result[$key] = $value->get();
            }
        }

        return $result;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function require($key = 'all')
    {
        if ($key === 'all') {
            foreach ($this->data as $column) {

            }
        }
    }

}