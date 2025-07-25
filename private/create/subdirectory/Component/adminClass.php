<?php
namespace Private\Important;
class adminClass
{
    protected $use;
    private $urlParameter;
    private const DEFAULT_URL_PARAMETER = 'create';
    public function __construct()
    {
        $this->use = new \Common\Important\UseClass();
        $this->setUrlParamter(self::DEFAULT_URL_PARAMETER);
    }

    public function setUrlParamter(string $urlParameter)
    {
        $this->urlParameter = $urlParameter;
    }

    /**
     * alert
     * 
     * アラートポップアップ出力(ページ遷移先は管理側ページ固定)
     *
     * @param string $message ポップアップに記載する文言
     * @param string|null $pathParameter ポップアップ出力後の遷移先のURL(nullの場合は遷移しない)
     * @return void
     */
    public function alert(string $message, ?string $pathParameter = null)
    {
        if (!is_null($pathParameter)) {
            $pathParameter = '/private/'. $pathParameter;
        }
        $this->use->alert($message, $pathParameter);
    }

    /**
     * alertError
     * 
     * エラー出力後、管理側ページに遷移(必ずページ遷移させる)
     *
     * @param string $message エラー文言
     * @return void
     */
    public function alertError(string $message)
    {
        $this->alert($message, $this->urlParameter);
    }
}
