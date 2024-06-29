<?php
/**
 * IoTrait
 * 
 * 入出力用のトレイト。
 * setPropertyでプロパティを設定している場合は、入力時に設定元のプロパティも更新する。
 */
trait IoTrait {
    private const DEFAULT_NAME = 'ioData';
    private string $ioName;
    private $ioData;

    /**
     * setProperty
     * 
     * 対象のプロパティ名を設定
     *
     * @param string|null $ioName 設定するプロパティ名
     * @return void
     */
    protected function setProperty(?string $ioName = self::DEFAULT_NAME): void
    {
        if (property_exists($this,$ioName)) {
            $this->ioName = $ioName;
        } else {
            $this->ioName = self::DEFAULT_NAME;
        }
    }

    /**
     * set
     * 
     * データを入力
     *
     * @param [type] $input
     * @return void
     */
    protected function set($input): void
    {
        $this->ioData = $input;

        // プロパティが正しくセットされている場合は、元のデータも一緒に更新
        if (isset($this->ioName) && $this->ioName !== self::DEFAULT_NAME) {
            $this->{$this->ioName} = $input;
        }
    }

    /**
     * getProperty
     * 
     * 現在設定中のプロパティ名を取得
     *
     * @return mixed
     */
    protected function getProperty(): mixed
    {
        return $this->ioName;
    }

    /**
     * getType
     * 
     * 設定中のプロパティのタイプを取得
     *
     * @return mixed
     */
    private function getType(): mixed
    {
        return gettype($this->ioData);
    }

    /**
     * get
     * 
     * データを取得
     *
     * @return mixed
     */
    protected function get(): mixed
    {
        return $this->ioData;
    }
}