<?php
/**
 * IoTrait
 * 
 * 入出力用のトレイト。
 * setPropertyでプロパティを設定している場合は、入力時に設定元のプロパティも更新する。
 */
trait IoTrait {
    private string $ioProperty;
    private $ioData;
    private bool $ioAutoSaveFlg = self::ON;
    protected const ON = true;
    protected const OFF = false;
    private const DEFAULT_NAME = 'data';
    private const DEFAULT_PROPERTY_NAMES = ['property', 'ioAutoSaveFlg', 'data'];

    /**
     * setProperty
     *
     * 対象のプロパティ名を設定し、プロパティに値が存在する場合は値を更新。
     *
     * @param string|null $ioProperty 設定するプロパティ名
     * @return void
     */
    protected function setProperty(string $ioProperty = self::DEFAULT_NAME): void
    {
        // プロパティ名をセット
        if (property_exists($this, $ioProperty)) {
            $this->ioProperty = $ioProperty;
        } else {
            $this->ioProperty = self::DEFAULT_NAME;
        }

        // プロパティの値をセット
        if (isset($this->{$this->ioProperty})) {
            $this->ioData = $this->{$this->ioProperty};
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
    protected function setValue($input): void
    {
        $this->ioData = $input;

        // 自動更新かつプロパティが正しくセットされている場合は、元のデータも一緒に更新
        $this->saveIo();
    }

    /**
     * autoSave
     * 
     * 自動更新の切り替え(デフォルトはオン)
     *
     * @return void
     */
    protected function autoSave(?bool $switching = null): void
    {
        if (is_null($switching)) {
            $this->ioAutoSaveFlg = !$this->ioAutoSaveFlg;
        } else {
            $this->ioAutoSaveFlg = $switching;
        }
    }

    /**
     * save
     * 
     * プロパティの値を更新
     *
     * @return void
     */
    protected function saveIo(): void
    {
        if (
            $this->ioAutoSaveFlg
            && isset($this->ioProperty)
            && $this->ioProperty !== self::DEFAULT_NAME
        ) {
            $this->{$this->ioProperty} = $this->ioData;
        }
    }

    /**
     * getProperty
     * 
     * 現在設定中のプロパティ名を取得
     *
     * @return string|false
     */
    protected function getProperty(): string|false
    {
        if (isset($this->ioProperty)) {
            $ioProperty = $this->ioProperty;
        } else {
            $ioProperty = false;
        }
        return $ioProperty;
    }

    /**
     * getAllProperty
     * 
     * 現在設定中のすべてのプロパティ名を取得
     * 
     * @return array
     */
    protected function getAllProperty(): array
    {
        $properties = get_object_vars($this);

        // IoTrait内のプロパティは除外
        foreach (self::DEFAULT_PROPERTY_NAMES as $deleteKey) {
            if (isset($properties[$deleteKey])) {
                unset($properties[$deleteKey]);
            }
        }

        return $properties;
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
     * getValue
     *
     * 設定中のプロパティの値を取得
     *
     * @return mixed
     */
    protected function getValue(): mixed
    {
        return $this->ioData;
    }
}