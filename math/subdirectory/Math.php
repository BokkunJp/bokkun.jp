<?php
ErrorConfig::noErrorMode();
try {
    // 公開側のTrait読み込み
    $path = new Path(PUBLIC_COMMON_DIR);
    $path->add('Trait');
    debug($path->get());
    includeFiles($path->get());

    class Math
    {
        // use PublicTrait;
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
} catch (Throwable $e) {
    return null;
}
