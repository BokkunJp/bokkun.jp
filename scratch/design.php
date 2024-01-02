<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
class Scratch
{
    use PublicTrait;
    private string $name;

    function __construct(string $init_name = '')
    {
        if (!empty($init_name)) {
            $this->setFuncName($init_name);
        }
    }

    public function setFuncName(string $name): void
    {
        $this->name = $name;
    }

    public function getFuncName(): string
    {
        return $this->name;
    }
}

$s_elm = new Scratch();
$s_elm->output('test');
