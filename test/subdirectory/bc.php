<?php
class BcClass {
    private float $value;

    private function set(string $val): void
    {
        $this->value = (float)$val;
    }

    public function get()
    {
        return $this->value;
    }

    public function bcfact($n): void
    {
        $r = $n--;
        while ($n > 1) {
            $r = bcmul($r, $n--);
        }

        $this->set($r);
    }

    public function bcsin($a)
    {
        $or = $a;
        $r = bcsub($a, bcdiv(bcpow($a, 3), 6));
        $i = 2;
        while(bccomp($or, $r)) {
            $or=$r;
            $this->bcfact($i * 2 + 1);
            $div = bcdiv(bcpow($a,$i * 2 + 1), $this->get());
            switch($i%2) {
                case 0:
                    $r = bcadd($r, $div);
                    break;
                default:
                    $r = bcsub($r,$div);
                    break;
            }
            $i++;
        }

        $this->set($r);
    }

    public function bccos($a)
    {
        $or = $a;
        $r = bcsub(1,bcdiv(bcpow($a,2),2));
        $i = 2;
        while (bccomp($or, $r)) {
            $or = $r;
            $this->bcfact($i * 2);
            $div = bcdiv(bcpow($a,$i*2), $this->get());
            switch ($i % 2) {
                case 0:
                    $r = bcadd($r, $div);
                    break;
                default:
                    $r = bcsub($r, $div);
                    break;
            }
            $i++;
        }

        $this->set($r);
    }
}

