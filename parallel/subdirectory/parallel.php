<?php
class Parallel
{
    private $data = null;

    public function __construct()
    {
        $this->firstProccessing();
    }

    private function firstProccessing(): void
    {
        if (PHP_SAPI === 'cli') {
            $this->data = new \parallel\Runtime();
        }
    }

    protected function intermediateProcessing(): bool
    {
        if ($this->data instanceof \parallel\Runtime) {
            return true;
        } else {
            return false;
        }
    }

    public function exectionProcessing(string $execName, ...$argv)
    {
        // CLI以外の場合はfalse
        if (!$this->intermediateProcessing()) {
            return false;
        }

        // CLIかつ、parallel拡張またはparallelクラスにあるメソッドが指定されたらそれぞれを実行
        $result = false;
        if (method_exists($this, $execName) ){
            $result = $this->$execName(...$argv);
        } elseif (method_exists($this->data, $execName)) {
            $result = $this->data->$execName($argv[0]);
        }

        return $result;
    }

    protected function run(callable $task)
    {
        return $this->data->run($task);
    }
}