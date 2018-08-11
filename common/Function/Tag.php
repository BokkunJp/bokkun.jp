<?php
// クラス化
class BaseClass {
    protected $authoritys;

    function __construct($init=true) {
        $this->Initialize($init);
    }

    protected function Initialize($init=false) {
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


    public function ViewAuthority($authorityName=null) {
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

class HTMLClass extends BaseClass {
    protected $tag, $tagName, $className, $contents;

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

    // TagSetでセットした情報に沿ってHTMLを生成する
    protected function TagCreate($setClass=false) {

        // a hrefのための特殊処理
        $a = array_search('a href', $this->authoritys);
        if (mb_strpos($this->tagName, $this->authoritys[$a]) !== false) {
            $this->authoritys[$a] = $this->tagName;
        }

        if (array_search($this->tagName, $this->authoritys) === false) {
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
        $this->tag = "<$this->tagName$class>$this->contents</$this->tagName>";
    }

    // タグ名・内容・クラス名をセットする
    public function TagSet($tagName='div', $contents=null, $className=null, $setClass=false) {
        $count = func_num_args();
        if ($count > 1) {
            $this->HTMLSet($tagName, $contents, $className);        // タグをHTML用のタグに置き換え
            unset($tagName);
            unset($contents);
            unset($className);
        } else if ($count === 1) {
            $setClass = $tagName;
            unset($tagName);
        }

        $this->TagCreate($setClass);
        unset($setClass);
    }

    protected function TagGet() {
        if (!isset($this->tag)) {
            trigger_error("タグが存在しません。", E_USER_ERROR);
        }

        return $this->tag;
    }

    public function TagExec($output=false, $spaceFlg=false) {
        if ($output === true) {
            echo $this->TagGet();
            if ($spaceFlg === true) {
                echo '<br/>';
            }
        }
            return $this->TagGet();
    }
}

class SpecialHTML extends HTMLClass {
    protected function ClosedTagCreate($tagName, $className, $setClass) {
        parent::TagSet($tagName, null, $className, $setClass);
        $this->tag = "<$this->tagName$className />";
    }

    protected function TagGet() {
        return parent::TagGet();
    }

    public function TagExec($view = false, $spaceFlg=false) {
        return parent::TagExec($view);
    }
}

class ScriptClass extends SpecialHTML {
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
   public function Alert($str, $abort=false) {
       $this->Script("alert('$str');");
       $this->TagExec(true);
       if ($abort === true) {
           exit;
       }
   }

    // Confirm関数 (結果をどのようにするか要検討)
   public function Confirm($str, $abort=false) {
       $ret = $this->Script("
          var ret = confirm('$str'); if (!ret) { exit(); }
          function exit(){ throw new Error; }
       ");
       $this->TagExec(true);
       if ($abort === true) {
           exit;
       }
   }

   // Prompt関数
   public function Prompt($str, $abort=false) {
       $this->Script("prompt('$str');");
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

   // Adminページへ遷移
   public function BackAdmin($query=null) {
    $this->MovePage('/private/'. $query);
   }
}

// 共通処理
// オリジナルダンプ
function deb_dump($value, $htmlspecialcharFlg=true) {
    if ($htmlspecialcharFlg === true) {
        $value = htmlspecialchars($value);
    }
    $newSpan = new HTMLClass();
    $newSpan->TagSet('span', $value, 'debug', true);
    $newSpan->TagExec(true);
    echo '<br/>';
}
