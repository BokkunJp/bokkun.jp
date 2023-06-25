<?php

namespace BasicTag;

// クラス化
class Base
{
    protected $authorities;

    public function __construct(bool $init = true)
    {
        $this->Initialize($init);
    }

    /**
     * Initialize
     *
     * 初期処理。
     *
     * @param boolean $init
     *
     * @return void
     */
    protected function Initialize(bool $init = false): void
    {
        if ($init === true) {
            $initArray = ['div', 'span', 'pre'];
        } else {
            $initArray = [];
        }
        unset($this->authorities);
        $this->authorities = array();
        $this->AllowAuthoritys($initArray);
    }

    /**
     * AllowAuthoritys
     *
     * @param array $authorities
     *
     * @return void
     */
    protected function AllowAuthoritys(array $authorities): void
    {
        foreach ($authorities as $value) {
            $this->authorities[] = $value;
        }
    }

    /**
     * AllowAuthority
     *
     * @param string $authority
     *
     * @return void
     */
    protected function AllowAuthority(string $authority): void
    {
        $this->AllowAuthoritys([$authority]);
    }

    /**
     * DenyAuthority
     *
     * @param string $authority
     *
     * @return void
     */
    protected function DenyAuthority(string $authority): void
    {
        $key = array_keys($this->authorities, $authority);
        $this->authorities = array_splice($this->authorities, $key[0], 1);
    }

    /**
     * SetDefault
     *
     * 権限を再初期化する。
     *
     * @return void
     */
    public function SetDefault(): void
    {
        $this->Initialize();
    }

    /**
     * ViewAuthority
     *
     * 権限を表示する。
     *
     * @param ?string $authorityName
     *
     * @return void
     */
    public function ViewAuthority(?string $authorityName = null): void
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
     * CreateAuthorityList
     *
     * タグ名リスト生成
     *
     * @param array $notuseList
     *
     * @return string
     */
    public function CreateAuthorityList(array $notuseList): string
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

class HTMLClass extends Base
{
    protected $tag;
    protected $tagName;
    protected $className;
    protected $contents;

    public function __construct(bool $init = false, array $allowTag = [])
    {
        parent::__construct($init);
        $this->AllowAuthoritys(array_merge($allowTag));
    }


    /**
     * SetTag
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
    public function SetTag(
        string $tagName = 'div',
        ?string $contents = '',
        string $className = '',
        string $tagOption = ''
    ) {
        $count = func_num_args();
        if ($count > 1) {
            $this->HTMLSet($tagName, $contents, $className);        // タグをHTML用のタグに置き換え
            unset($tagName);
            unset($contents);
            unset($className);
        } else {
            $this->tagName = $tagName;
            unset($tagName);
        }

        $this->CreateTag($tagOption);    // SetTagでセットした情報に沿ってHTMLを生成する
    }

    // HTMLの各要素をセットする
    protected function HTMLSet(string $tagName, ?string $contents, string $className): void
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
     * SetSpecailContents
     *
     * @param ?string $contents
     * @return void
     */
    protected function SetSpecailContents(?string $contents): void
    {
        $this->HTMLSet($this->tagName, $this->contents, $this->className);
        $this->contents = $contents;
    }

    // タグ名などのメタデータに沿ってHTMLを生成する
    protected function CreateTag(?string $tagOption = ''): void
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
            trigger_error("タグ名が不正です。", E_USER_ERROR);
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
     * GetTag
     *
     * タグを取得する。
     *
     * @return string
     */
    protected function GetTag(): string
    {
        if (!isset($this->tag)) {
            user_error("タグが存在しません。");
        }

        return $this->tag;
    }

    /**
     * ExecTag
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
    public function ExecTag(
        $output = false,
        $formatFlg = false,
        $indentFlg = false,
        $dumpFlg = false,
        array $debug = []
    ): string {
        if ($output === true) {
            Output($this->GetTag(), $formatFlg, $indentFlg, $dumpFlg, $debug);
        }
        return $this->GetTag();
    }

    /**
     * Clean
     *
     * authoritry以外の内部変数を初期化する
     *
     * @param mixed $elm
     *
     * @return void
     */
    public function Clean(mixed $elm = null)
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
     * CreateClosedTag
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
    protected function CreateClosedTag(
        string $tagName,
        string $tagOption,
        string $className,
        bool $viewLink = false
    ): ?string {
        parent::SetTag($tagName, null, $className);
        $this->tag = substr_replace($this->tag, $tagOption . " />", strcspn($this->tag, '>', 0));
        return $this->ExecTag($viewLink);
    }

    /**
     * CreateDiffTag
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
    private function CreateDiffTag(
        string $tagName,
        string $link,
        ?string $title = null,
        ?string $class = '',
        bool $viewLink = false
    ) {
        $this->SetTag($tagName, $title, $class, $link);
        return $this->ExecTag($viewLink);
    }

    /**
     * SetImage
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
    public function SetImage(
        string $link = '',
        int $width = 400,
        int $height = 400,
        ?string $class = '',
        bool$viewLink = false
    ) {
        return $this->CreateClosedTag("img", " src='$link' width=" . $width . "px height=" . $height . "px", $class, $viewLink);
    }

    /**
     * SetHref
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
    public function SetHref(
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
        return $this->CreateDiffTag("a href", $link, $title, $class, $viewLink);
    }

    // script src
    public function ReadJS(string $link = 'link', ?string $class = '', bool $viewLink = false)
    {
        return $this->CreateDiffTag("script src", $link, null, $class, $viewLink);
    }
}

// スクリプトタグの処理
class ScriptClass extends HTMLClass
{
    protected $script;

    public function __construct()
    {
        parent::__construct();
        $this->AllowAuthority('script');
    }

    /**
     * Script
     *
     * scriptタグのセット。
     *
     * @param string $str
     *
     * @return void
     */
    public function Script(string $str): void
    {
        $this->SetTag('script', $str);
    }

    // Alert関数
    public function Alert(string $str, bool $abort = false): void
    {
        $this->Script("alert('$str');");
        $this->ExecTag(true);
        if ($abort === true) {
            exit;
        }
    }
}

class UseClass extends ScriptClass
{
    /**
     * MovePage
     *
     * 指定したURLへ遷移。
     *
     * @param string $url
     *
     * @return void
     */
    public function MovePage(string $url): void
    {
        $this->Script("location.href='$url';");
        $this->ExecTag(true);
    }

    public function Confirm()
    {
    }
}

/**
 * deb_dump
 *
 * オリジナルダンプ。
 *
 * @param mixed $value
 * @param boolean $htmlspecialcharFlg
 *
 * @return void
 */
function deb_dump(mixed $value, bool $htmlspecialcharFlg = true): void
{
    if ($htmlspecialcharFlg === true) {
        $value = htmlspecialchars($value);
    }
    $newSpan = new HTMLClass();
    $newSpan->SetTag('span', $value, 'debug');
    $newSpan->ExecTag(true);
    echo '<br/>';
}
