<?php

namespace Common\Important;

// クラス化
class Tag
{
    protected $authorities;

    public function __construct(bool $init = true)
    {
        $this->initialize($init);
    }

    /**
     * initialize
     *
     * 初期処理。
     *
     * @param boolean $init
     *
     * @return void
     */
    protected function initialize(bool $init = false): void
    {
        if ($init === true) {
            $initArray = ['div', 'span', 'pre'];
        } else {
            $initArray = [];
        }
        unset($this->authorities);
        $this->authorities = array();
        $this->allowAuthoritys($initArray);
    }

    /**
     * allowAuthoritys
     *
     * @param array $authorities
     *
     * @return void
     */
    protected function allowAuthoritys(array $authorities): void
    {
        foreach ($authorities as $value) {
            $this->authorities[] = $value;
        }
    }

    /**
     * allowAuthority
     *
     * @param string $authority
     *
     * @return void
     */
    protected function allowAuthority(string $authority): void
    {
        $this->allowAuthoritys([$authority]);
    }

    /**
     * DenyAuthority
     *
     * @param string $authority
     *
     * @return void
     */
    protected function denyAuthority(string $authority): void
    {
        $key = array_keys($this->authorities, $authority);
        $this->authorities = array_splice($this->authorities, $key[0], 1);
    }

    /**
     * setDefault
     *
     * 権限を再初期化する。
     *
     * @return void
     */
    public function setDefault(): void
    {
        $this->initialize();
    }

    /**
     * viewAuthority
     *
     * 権限を表示する。
     *
     * @param ?string $authorityName
     *
     * @return void
     */
    public function viewAuthority(?string $authorityName = null): void
    {
        if (!isset($authorityName)) {
            foreach ($this->authorities as $value) {
                var_dump("$value is true.");
            }
        } else {
            var_dump("$authorityName is true.");
        }
    }

    /**
     * createAuthorityList
     *
     * タグ名リスト生成
     *
     * @param array $notuseList
     *
     * @return string
     */
    public function createAuthorityList(array $notuseList): string
    {
        $select = '<select>';
        $authorityList = $this->authorities;
        $notuse = array_search('script', $authorityList);
        if (isset($notuse)) {
            foreach ($notuseList as $notuse) {
                $keys = array_keys($authorityList, $notuse);
                foreach ($keys as $key) {
                    unset($authorityList[$key]);            // 不要なタグの削除
                }
            }
        }
        foreach ($authorityList as $value) {
            $select .= "<option>$value</option>";
        }
        $select .= '</select>';

        return $select;
    }
}

class HTMLClass extends Tag
{
    protected $tag;
    protected $tagName;
    protected $className;
    protected $contents;

    public function __construct(bool $init = false, array $allowTag = [])
    {
        parent::__construct($init);
        $this->allowAuthoritys(array_merge($allowTag));
    }


    /**
     * setTag
     *
     * タグ名・内容・クラス名をセットする
     *
     * @param string $tagName
     * @param string|null $contents
     * @param string $className
     * @param string $tagOption
     *
     * @return void
     */
    public function setTag(
        string $tagName = 'div',
        ?string $contents = '',
        string $className = '',
        string $tagOption = ''
    ) {
        $count = func_num_args();
        if ($count > 1) {
            $this->setHtml($tagName, $contents, $className);        // タグをHTML用のタグに置き換え
            unset($tagName);
            unset($contents);
            unset($className);
        } else {
            $this->tagName = $tagName;
            unset($tagName);
        }

        $this->createTag($tagOption);    // SetTagでセットした情報に沿ってHTMLを生成する
    }

    // HTMLの各要素をセットする
    protected function setHtml(string $tagName, ?string $contents, string $className): void
    {
        $this->tagName = $tagName;

        $tags = '';
        foreach ($this->authorities as $value) {
            $tags .= "<$value>";
        }
        if (is_string($contents)) {
            $this->contents = strip_tags($contents, $tags);
        } else {
            $this->contents = $contents;
        }
        $this->className = $className;
    }

    /**
     * setSpecailContents
     *
     * @param ?string $contents
     * @return void
     */
    protected function setSpecailContents(?string $contents): void
    {
        $this->setHtml($this->tagName, $this->contents, $this->className);
        $this->contents = $contents;
    }

    // タグ名などのメタデータに沿ってHTMLを生成する
    protected function createTag(?string $tagOption = ''): void
    {
        // 開始タグと終了タグでタグ名が違うタグ(a hrefなど)のための特殊処理
        $tagAuth = $this->tagName;
        foreach ($this->authorities as $_authorities) {
            if (mb_strpos($_authorities, ' ') !== false) {
                if ($this->tagName === $_authorities) {
                    $this->tagName .= '=' . $tagOption;
                    $tagEnd = explode(' ', $_authorities)[0];
                }
            }
        }

        if (array_search($tagAuth, $this->authorities) === false) {
            throw new Error("引数が不正です。");
        }

        if (!empty($this->className)) {
            $class = "class='$this->className'";
        } else {
            $class = null;
        }
        $this->tag = "<$this->tagName $class>$this->contents";
        // 開始タグと終了タグでタグ名が違う場合 (<a href>...</a>など)
        if (isset($tagEnd)) {
            $this->tagName = $tagEnd;
            unset($tagEnd);
        }
        $this->tag .= "</$this->tagName>";
    }

    /**
     * getTag
     *
     * タグを取得する。
     *
     * @return string
     */
    protected function getTag(): string
    {
        if (!isset($this->tag)) {
            user_error("タグが存在しません。");
        }

        return $this->tag;
    }

    /**
     * execTag
     *
     * タグを取得して表示する。
     *
     * @param boolean $output
     * @param boolean $formatFlg
     * @param boolean $indentFlg
     * @param boolean $dumpFlg
     * @param array $debug
     *
     * @return string
     */
    public function execTag(
        $output = false,
        $formatFlg = false,
        $indentFlg = false,
        $dumpFlg = false,
        array $debug = []
    ): string {
        if ($output === true) {
            output($this->getTag(), $formatFlg, $indentFlg, $dumpFlg, $debug);
        }
        return $this->getTag();
    }

    /**
     * clean
     *
     * authoritry以外の内部変数を初期化する
     *
     * @param mixed $elm
     *
     * @return void
     */
    public function clean(mixed $elm = null)
    {
        // 初期化する変数の指定がある場合
        if (!is_null($elm)) {
            if (!is_array($elm)) {
                if (property_exists($this, $elm)) {
                    $this->$elm = null;
                }
            } else {
                foreach ($elm as $_key => $_deleteList) {
                    if (property_exists($this, $_deleteList)) {
                        $this->$_deleteList = null;
                    }
                }
            }
            // 初期化する変数の指定がない場合(authority以外の全変数初期化)
        } else {
            $elmList = get_object_vars($this);
            unset($elmList['authorities']);                  // authorityは除外
            foreach ($elmList as $_deleteList => $_value) {
                $this->$_deleteList = null;
            }
        }
    }
}

// 特殊タグ用の処理
class CustomTagCreate extends HTMLClass
{
    public function __construct(
        bool $init = true,
        array $initArray=['a href', 'script src', 'img']
    ) {
        parent::__construct($init, $initArray);
    }

    /**
     * createClosedTag
     *
     * 閉じタグがないタグを作成する。
     *
     * @param string $tagName
     * @param string $tagOption
     * @param string $className
     * @param boolean $viewLink
     *
     * @return ?string
     */
    protected function createClosedTag(
        string $tagName,
        string $tagOption,
        string $className,
        bool $viewLink = false
    ): ?string {
        parent::setTag($tagName, null, $className);
        $this->tag = substr_replace($this->tag, $tagOption . " />", strcspn($this->tag, '>', 0));
        return $this->execTag($viewLink);
    }

    /**
     * createDiffTag
     *
     * aタグ、imgタグやscriptタグなどのタグ内でオプションを設定する必要があるタグの生成。
     *
     * @param string $tagName
     * @param string $link
     * @param ?string $title
     * @param ?string $class
     * @param boolean $viewLink
     *
     * @return void
     */
    private function createDiffTag(
        string $tagName,
        string $link,
        ?string $title = null,
        ?string $class = '',
        bool $viewLink = false
    ) {
        $this->setTag($tagName, $title, $class, $link);
        return $this->execTag($viewLink);
    }

    /**
     * setImage
     *
     * imgタグをセット。
     *
     * @param string $link
     * @param integer $width
     * @param integer $height
     * @param ?string $class
     *
     * @param boolean $viewLink
     * @return void
     */
    public function setImage(
        string $link = '',
        int $width = 400,
        int $height = 400,
        ?string $class = '',
        bool$viewLink = false
    ) {
        return $this->createClosedTag("img", " src='$link' width=" . $width . "px height=" . $height . "px", $class, $viewLink);
    }

    /**
     * setHref
     *
     * aタグをセット。
     *
     * @param string $link
     * @param ?string $title
     * @param ?string $class
     * @param boolean $viewLink
     * @param ?string $target
     *
     * @return void
     */
    public function setHref(
        string $link = '',
        ?string $title = null,
        ?string $class = 'test',
        bool $viewLink = false,
        ?string $target = '_new'
    ) {
        if (preg_match('/^_/', $target) === 0) {
            $target = '_' . $target;
        }
        switch ($target) {
            case '_blank':
                $target .= ' rel="noopener"';
                break;
            case '_new':
                break;
            case '_top':
                break;
            case '_self':
                break;
            case '_parent':
                break;
            default:
                user_error('ターゲットの選択が不正です。', E_RECOVERABLE_ERROR);
                break;
        }
        $class .= '\' target=\'' . $target;
        return $this->createDiffTag("a href", $link, $title, $class, $viewLink);
    }

    // script src
    public function readJs(string $link = 'link', ?string $class = '', bool $viewLink = false)
    {
        return $this->createDiffTag("script src", $link, null, $class, $viewLink);
    }
}

// スクリプトタグの処理
class ScriptClass extends HTMLClass
{
    protected $script;

    public function __construct()
    {
        parent::__construct();
        $this->allowAuthority('script');
    }

    /**
     * script
     *
     * scriptタグのセット。
     *
     * @param string $str
     *
     * @return void
     */
    public function script(string $str): void
    {
        $this->setTag('script', $str);
    }

    // alert関数
    public function alert(string $str, bool $abort = false): void
    {
        $this->script("alert('$str');");
        $this->execTag(true);
        if ($abort === true) {
            exit;
        }
    }
}

class UseClass extends ScriptClass
{
    /**
     * movePage
     *
     * 指定したURLへ遷移。
     *
     * @param string $url
     *
     * @return void
     */
    public function movePage(string $url): void
    {
        $this->script("location.href='$url';");
        $this->execTag(true);
    }

    public function confirm()
    {
    }
}

/**
 * debugDump
 *
 * オリジナルダンプ。
 *
 * @param mixed $value
 * @param boolean $htmlspecialcharFlg
 *
 * @return void
 */
function debugDump(mixed $value, bool $htmlspecialcharFlg = true): void
{
    if ($htmlspecialcharFlg === true) {
        $value = htmlspecialchars($value);
    }
    $newSpan = new HTMLClass();
    $newSpan->setTag('span', $value, 'debug');
    $newSpan->execTag(true);
    echo '<br/>';
}
