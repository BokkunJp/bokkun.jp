<?php
/**
 * IoTrait
 * 
 * 入出力用のトレイト。
 * setPropertyでプロパティを設定している場合は、入力時に設定元のプロパティも更新する。
 */
trait IoTrait {
    private const DEFAULT_NAME = 'data';
    private string $property;
    private bool $autoSaveFlg = true;
    private $data;

    /**
     * setProperty
     *
     * 対象のプロパティ名を設定し、プロパティに値が存在する場合は値を更新。
     *
     * @param string|null $property 設定するプロパティ名
     * @return void
     */
    protected function setProperty(string $property = self::DEFAULT_NAME): void
    {
        // プロパティ名をセット
        if (property_exists($this, $property)) {
            $this->property = $property;
        } else {
            $this->property = self::DEFAULT_NAME;
        }

        // プロパティの値をセット
        if (isset($this->{$this->property})) {
            $this->data = $this->{$this->property};
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
        $this->data = $input;

        // 自動更新かつプロパティが正しくセットされている場合は、元のデータも一緒に更新
        $this->save();
    }

    /**
     * convertAutoSave
     * 
     * 自動更新の切り替え(デフォルトはオン)
     *
     * @return void
     */
    protected function convertAutoSave(): void
    {
        $this->autoSaveFlg = !$this->autoSaveFlg;
    }

    /**
     * save
     * 
     * プロパティの値を更新
     *
     * @return void
     */
    protected function save(): void
    {
        if (
            $this->autoSaveFlg
            && isset($this->property)
            && $this->property !== self::DEFAULT_NAME
        ) {
            $this->{$this->property} = $this->data;
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
        if (isset($this->property)) {
            $property = $this->property;
        } else {
            $property = false;
        }
        return $property;
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
        return gettype($this->data);
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
        return $this->data;
    }
}