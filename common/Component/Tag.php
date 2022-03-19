<?php

namespace BasicTag;

// クラス化
class Base
{
    protected $authorities;

    public function __construct($init = true)
    {
        $this->Initialize($init);
    }

    protected function Initialize($init = false)
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

    protected function AllowAuthoritys($authorities)
    {
        if (!is_array($authorities)) {
            trigger_error("引数が不正です。", E_USER_ERROR);
        }
        foreach ($authorities as $value) {
            $this->authorities[] = $value;
        }
    }

    protected function AllowAuthority($authority)
    {
        $this->AllowAuthoritys([$authority]);
    }

    protected function DenyAuthority($authority)
    {
        $key = array_keys($this->authorities, $authority);
        $this->authorities = array_splice($this->authorities, $key[0], 1);
    }

    public function SetDefault()
    {
        $this->Initialize();
    }

    public function ViewAuthority($authorityName = null)
    {
        if (!isset($authorityName)) {
            foreach ($this->authorities as $value) {
                var_dump("$value is true.");
            }
        } else {
            var_dump("$authorityName is true.");
        }
    }

    // タグ名リスト生成
    public function CreateAuthorityList($notuseList)
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
            if (mb_strpos($value, 'a href') !== false) {
//                $value = 'a';
            }
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

    public function __construct($init=false, $allowTag=[])
    {
        parent::__construct($init);
        $this->AllowAuthoritys(array_merge($allowTag));
    }


    // タグ名・内容・クラス名をセットする
    public function SetTag($tagName = 'div', $contents = '', $className = '', $tagOption = '')
    {
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
    protected function HTMLSet($tagName, $contents, $className)
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

    protected function SetSpecailContents($contents)
    {
        $this->HTMLSet($this->tagName, $this->contens, $this->className);
        $this->contents = $contents;
    }

    // タグ名などのメタデータに沿ってHTMLを生成する
    protected function CreateTag($tagOption = '')
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

    protected function GetTag()
    {
        if (!isset($this->tag)) {
            trigger_error("タグが存在しません。", E_USER_ERROR);
        }

        return $this->tag;
    }

    public function ExecTag($output = false, $spaceFlg = false)
    {
        if ($output === true) {
            echo $this->GetTag();
            if ($spaceFlg === true) {
                echo nl2br("\n");
            }
        }
        return $this->GetTag();
    }

    // authoritry以外の内部変数を初期化する
    public function Clean($elm = null)
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
    public function __construct($init=true, $initArray=['a href', 'script src', 'img'])
    {
        parent::__construct($init, $initArray);
    }

    protected function CreateClosedTag($tagName, $tagOption, $className, $viewLink = false)
    {
        parent::SetTag($tagName, null, $className);
        $this->tag = substr_replace($this->tag, $tagOption . " />", strcspn($this->tag, '>', 0));
        return $this->ExecTag($viewLink);
    }

    protected function GetTag()
    {
        return parent::GetTag();
    }

    public function ExecTag($view = false, $spaceFlg = false)
    {
        return parent::ExecTag($view);
    }

    private function CreateDiffTag($tagName, $link, $title = null, $class = '', $viewLink = false)
    {
        $this->SetTag($tagName, $title, $class, $link);
        return $this->ExecTag($viewLink);
    }

    // img src
    public function SetImage($link = '', $width = 400, $height = 400, $class = '', $viewLink = false)
    {
        return $this->CreateClosedTag("img", " src='$link' width=" . $width . "px height=" . $height . "px", $class, $viewLink);
    }

    // a href
    public function SetHref($link = '', $title = null, $class = 'test', $viewLink = false, $target = '_new')
    {
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
    public function ReadJS($link = 'aaa', $class = '', $viewLink = false)
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

    // Scriptタグ
    public function Script($str)
    {
        $this->SetTag('script', $str);
    }

    // Alert関数
    public function Alert($str, $abort = false)
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
    // 指定したURLへ遷移
    public function MovePage($url)
    {
        $this->Script("location.href='$url';");
        $this->ExecTag(true);
    }

    public function Confirm()
    {
    }
}

// 共通処理
// オリジナルダンプ
function deb_dump($value, $htmlspecialcharFlg = true)
{
    if ($htmlspecialcharFlg === true) {
        $value = htmlspecialchars($value);
    }
    $newSpan = new HTMLClass();
    $newSpan->SetTag('span', $value, 'debug');
    $newSpan->ExecTag(true);
    echo '<br/>';
}
