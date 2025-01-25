<?php
try {
    // 公開側のTrait読み込み
    $path = new Path(PUBLIC_COMMON_DIR);
    $path->add('Trait');
    includeFiles($path->get());
} catch (Exception $e) {
    new Exception('error');
}

class RangeCode
{
    use ioTrait;
    private $message;
    private $range;

    function __construct(string $message)
    {
        $this->setProperty('message');
        $this->set($message);
    }
}