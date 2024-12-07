<?php
$path = new PathApplication("math", COMMON_DIR);
class Math
{
    use PublicTrait;
    use IoTrait;
    private float $value;

    public function __construct(?float $data = null)
    {
        if (!empty($data)) {
            $this->setData($data);
        }
    }

    /**
     * 入力値をセットする
     *
     * @param float $data
     * 
     * @return void
     */
    public function setData(float $data)
    {
        $this->value = $data;
    }

    /**
     * 値を出力する。
     *
     * @return void
     */
    public function getData()
    {
        return $this->value;
    }
}
