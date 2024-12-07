<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$all = new Common\Important\Session();
$imageView = new Public\Important\Session('image-view');
$test2 = new Public\Important\Session('test2');
// $all->writeArray('test', '12345', [1]);
// $test->write('test1', [2]);
// $test->write('test1-2', 'abc');
// $test->writeArray('aaa', 'bbbb', 'c');
$all->view();
echo "<hr/>";
$imageView->view();
echo "<hr/>";
$test2->view();

class MathBasic {
    private array $strageData = [];

    private function commonProcess(string $operator)
    {
        $result = 0;

        switch ($operator)
        {
            case 'add':
                foreach ($this->strageData as $data)
                {
                    $result += $data;
                }
            break;
            case 'sub':
                foreach ($this->strageData as $data)
                {
                    $result -= $data;
                }
            break;
            case 'mul':
                foreach ($this->strageData as $data)
                {
                    $result *= $data;
                }
            break;
            case 'div':
                foreach ($this->strageData as $data)
                {
                    if ($data !== 0) {
                        $result /= $data;
                    }
                }
            break;
            default:
                $result = false;
            break;
        }

        return $result;
    }

    public function setValue(int|float $value)
    {
        $this->strageData[] = $value;
    }

    public function resetValue(bool $onceFlg = false)
    {
        if (!$onceFlg) {
            $this->strageData = [];
        } else {
            array_pop($this->strageData);
        }
    }

    public function add(): int|float|bool
    {
        return $this->commonProcess('add');
    }

    public function sub(): int|float|bool
    {
        return $this->commonProcess('sub');
    }

    public function mul(): int|float|bool
    {
        return $this->commonProcess('mul');
    }

    public function div(): int|float|bool
    {
        return $this->commonProcess('div');
    }
}

$test = new MathBasic();
debug($test);
$test->setValue(1);
debug($test->add());
$test->setValue(2);
debug($test->add());