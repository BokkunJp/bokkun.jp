<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
class Scratch
{
    use PublicTrait;
    private string $name;

    function __construct(string $init_name = '')
    {
        $this->name = $init_name;
    }

    public function getFuncName()
    {
        return $this->name;
    }
}
$s_elm = new Scratch();
