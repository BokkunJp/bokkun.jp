<?php

// クラス化
class BaseTag {

    protected $authoritys;

    function __construct($init = true) {
        $this->Initialize($init);
    }

    protected function Initialize($init = false) {
        if ($init === true) {
            $initArray = ['div', 'span'];
        } else {
            $initArray = [];
        }
        unset($this->authoritys);
        $this->authoritys = array();
        $this->AllowAuthoritys($initArray);
    }

    protected function AllowAuthoritys($authoritys) {
        if (!is_array($authoritys)) {
            trigger_error("引数が不正です。", E_USER_ERROR);
        }
        foreach ($authoritys as $value) {
            $this->authoritys[] = $value;
        }
    }

    protected function AllowAuthority($authority) {
        $this->AllowAuthoritys([$authority]);
    }

    protected function DenyAuthority($authority) {
        $key = array_keys($this->authoritys, $authority);
        $this->authoritys = array_splice($this->authoritys, $key, 1);
    }

    public function SetDefault() {
        $this->Initialize();
    }

    public function ViewAuthority($authorityName = null) {
        if (!isset($authorityName)) {
            foreach ($this->authoritys as $value) {
                var_dump("$value is true.");
            }
        } else {
            var_dump("$authorityName is true.");
        }
    }

    // タグ名リスト生成
    public function authorityListCreate($notuseList) {
        $select = '<select>';
        $authorityList = $this->authoritys;
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

class HTMLClass extends BaseTag {

    protected $tag, $tagName, $className, $contents;

    // タグ名・内容・クラス名をセットする
    public function TagSet($tagName = 'div', $contents = '', $className = '', $setClass = false, $tagOption = '') {
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

        $this->TagCreate($setClass, $tagOption);    // TagSetでセットした情報に沿ってHTMLを生成する
        unset($setClass);
    }

    // HTMLの各要素をセットする
    protected function HTMLSet($tagName, $contents, $className) {
        $this->tagName = $tagName;

        $tags = '';
        foreach ($this->authoritys as $value) {
            $tags .= "<$value>";
        }
        if (is_string($contents)) {
            $this->contents = strip_tags($contents, $tags);
        } else {
            $this->contents = $contents;
        }
        $this->className = $className;
    }

    protected function SpecailContentsSet($contents) {
        parent::HTMLSet($this->$tagName, $this->contens, $this->className);
        $this->contents = $contents;
    }

    // タグ名などのメタデータに沿ってHTMLを生成する
    protected function TagCreate($setClass = false, $tagOption = '') {
        // 開始タグと終了タグでタグ名が違うタグ(a hrefなど)のための特殊処理
        $tagAuth = $this->tagName;
        foreach ($this->authoritys as $_authoritys) {
            if (mb_strpos($_authoritys, ' ') !== false) {
                if ($this->tagName === $_authoritys) {
                    $this->tagName .= '=' . $tagOption;
                    $tagEnd = explode(' ', $_authoritys)[0];
                }
            }
        }

        if (array_search($tagAuth, $this->authoritys) === false) {
            trigger_error("タグ名が不正です。", E_USER_ERROR);
        }

        if ($setClass === true) {
            if (!isset($this->className)) {
                $this->className = $this->tagName;
            }
            $class = " class='$this->className'";
        } else {
            $class = null;
        }
        $this->tag = "<$this->tagName$class>$this->contents";
        // 開始タグと終了タグでタグ名が違う場合 (<a href>...</a>など)
        if (isset($tagEnd)) {
            $this->tagName = $tagEnd;
            unset($tagEnd);
        }
        $this->tag .= "</$this->tagName>";
    }

    protected function TagGet() {
        if (!isset($this->tag)) {
            trigger_error("タグが存在しません。", E_USER_ERROR);
        }

        return $this->tag;
    }

    public function TagExec($output = false, $spaceFlg = false) {
        if ($output === true) {
            echo $this->TagGet();
            if ($spaceFlg === true) {
                echo nl2br("\n");
            }
        }
        return $this->TagGet();
    }

    // authoritry以外の内部変数を初期化する
    function Clean($elm = null) {
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
            unset($elmList['authoritys']);                  // authorityは除外
            foreach ($elmList as $_deleteList => $_value) {
                $this->$_deleteList = null;
            }
        }
    }

}

// 特殊タグ用の処理
class CustomTagCreate extends HTMLClass {

    function __construct() {
        parent::__construct();
        $this->AllowAuthoritys(['a href', 'script src', 'img']);
    }

    protected function CreateClosedTag($tagName, $tagOption, $className, $setClass, $viewLink = false) {
        parent::TagSet($tagName, null, $className, $setClass);
        $this->tag = substr_replace($this->tag, $tagOption . " />", strcspn($this->tag, '>', 0));
        return $this->TagExec($viewLink);
    }

    protected function TagGet() {
        return parent::TagGet();
    }

    public function TagExec($view = false, $spaceFlg = false) {
        return parent::TagExec($view);
    }

    private function CreateDiffTag($tagName, $link, $title = null, $class = '', $viewLink = false) {
        $this->TagSet($tagName, $title, $class, true, $link);
        return $this->TagExec($viewLink);
    }

    // img src
    public function SetImage($link = '', $width = 400, $height = 400, $setClass = true, $class = '', $viewLink = false) {
        return $this->CreateClosedTag("img", " src='$link' width=" . $width . "px height=" . $height . "px", $class, $setClass, $viewLink);
    }

    // a href
    public function SetHref($link = '', $title = null, $class = 'test', $viewLink = false, $target = '_new') {
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
                user_error('ターゲットの選択が不正です。');
                break;
        }
        $class .= '\' target=\'' . $target;
        return $this->CreateDiffTag("a href", $link, $title, $class, $viewLink);
    }

    // script src
    public function ReadJS($link = 'aaa', $class = '', $viewLink = false) {
        return $this->CreateDiffTag("script src", $link, null, $class, $viewLink);
    }

}

// スクリプトタグの処理
class ScriptClass extends HTMLClass {

    protected $script;

    function __construct() {
        parent::__construct();
        $this->AllowAuthority('script');
    }

    // Scriptタグ
    public function Script($str) {
        $this->TagSet('script', $str);
    }

    // Alert関数
    public function Alert($str, $abort = false) {
        $this->Script("alert('$str');");
        $this->TagExec(true);
        if ($abort === true) {
            exit;
        }
    }

}

class UseClass extends ScriptClass {

    // 指定したURLへ遷移
    public function MovePage($url) {
        $this->Script("location.href='$url';");
        $this->TagExec(true);
    }

    // メインページへ遷移
    public function BackPage($query = null) {
        $this->MovePage('/public/' . $query);
    }

}

// 共通処理
// オリジナルダンプ
function deb_dump($value, $htmlspecialcharFlg = true) {
    if ($htmlspecialcharFlg === true) {
        $value = htmlspecialchars($value);
    }
    $newSpan = new HTMLClass();
    $newSpan->TagSet('span', $value, 'debug', true);
    $newSpan->TagExec(true);
    echo '<br/>';
}
