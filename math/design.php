<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
class MathApp
{
    use PublicTrait;
    private float $value;

    public function __construct(?float $data = null)
    {
        if (!empty($data)) {
            $this->setData($data);
        }
    }

    public function setData($data)
    {
        
    }
}

$mathApp = new MathApp();