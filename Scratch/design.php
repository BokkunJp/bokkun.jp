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
debug($s_elm);
$s_elm->output('test');
$s_elm->debug("aaa");
$functionExsitsFlg = function_exists('mini');
if ($functionExsitsFlg) {
    call_user_func('mini');
}

function mini()
{
    echo "buy.<br/>";
}
