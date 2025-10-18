<?php
class Parallel
{
    private $data = null;
    private $argv;

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

    public function exec(string $execName, ...$argv)
    {
        $this->adjustmentElement($argv);

        // CLI以外の場合はfalse
        if (
            !$this->intermediateProcessing()
        ) {
            return false;
        }

        // CLIかつ、parallel拡張またはparallelクラスにあるメソッドが指定されたらそれぞれを実行
        if (
            method_exists($this, $execName)
        ) {
            $this->$execName($argv);
        } elseif (
            method_exists($this->data, $execName)
        ) {
            $this->data->$execName($argv[0]);
        }

    }

        protected function run($argv)
        {
            $this->data->run($argv[0]);
        }

        private function adjustmentElement($argv): int
        {
            foreach ($argv as $key => $value) {
                if ($key < 2) {
                    $this->argv[$key] = $value;
                }
            }

            return $key - 1;
        } 
}