<?php
function APIPath() {
    return md5();
}

// Setting.phpに移動
// (Sessionクラスと連携)
class Method {
    private $post;
    private $get;

    function __construct() {
        $this->Initialize();
        $this->post = $_POST;
        $this->get = $_GET;
    }

    public function Initialize($mode=null) {
        switch ($mode) {
            case 'get':
            $this->get = null;
            break;
            case 'post':
            $this->post = null;
            break;
            default:
            $this->Initialize('get');
            $this->Initialize('post');
            break;
            
        }
    }

    public function ViewPost($elm=null) {
        $this->View('Post', $elm);
    }
    
    public function ViewGet($elm=null) {
        $this->View('Get', $elm);
    }

    protected function View($mode, $elm=null) {
        switch($mode) {
            case 'Post':
            if (isset($elm)) {
                echo $this->post[$elm];
            } else {
                var_dump($this->post);
            }
            break;
            case 'Get':
            if (isset($elm)) {
                echo $this->get[$elm];
            } else {
                var_dump($this->get);
            }
            break;
            default:
            break;
        }
    }

    public function AddPost($elm, $value) {
        $this->post[$elm] = $value;
        $this->SavePost();
    }

    public function AddGet($elm, $value) {
        $this->get[$elm] = $value;
        $this->SaveGet();
    }

    public function DeletePost($elm) {
        if ($elm=null || isset($post[$elm])) {
            $this->Initialize('post');
        } else {
            unset($post[$elm]);
        }
        $this->SavePost();
    }

    public function DeleteGet($elm) {
        if ($elm=null || isset($get[$elm])) {
            $this->Initialize('get');
        } else {
            unset($get[$elm]);
        }
        $this->SaveGet();
    }

    private function SavePost() {
        $_POST = $this->post;
    }

    private function SaveGet() {
        $_GET = $this->get;
    }
}

/* 
 * ディレクトリ・ファイルの作成・コピー
 * 引数：   
 *          $mode：ファイルかディレクトリのどちらかを選択 (file, directory)
 *          $action：ファイル作成化ファイルコピー化を選択(make, copy)
 *          $name：ファイル名 (デフォルトは'test')
 *          $permission：パーミッション(デフォルトは777)
 *          $dirPath：ディレクトリパス(デフォルトはカレントディレクトリ)

 */
function Make($mode, $action, $name='test', $permission=777, $dirPath=__DIR__) {
    $validate = Validate($mode, 'null');
    if ($validate === false) {
        echo 'error2';
        return false;
    }

    $validate = ['file'=> Validate($mode, 'string', 'file'), 'directory'=> Validate($mode, 'string', 'directory')];

    if ($validate['file'] === false && $validate['directory'] === false) {
        echo 'error3';
        return false;
    }

    $validate = Validate($action, 'null');
    if ($validate === false) {
        echo 'error4';
        return false;
    }

    $validate = ['create'=> Validate($action, 'string', 'create'), 'copy'=> Validate($action, 'string', 'copy')];
    if ($validate['create'] === false && $validate['copy'] === false) {
        echo 'error5';
        return false;
    }

    echo 'success';
    die;

    chdir($dirPath);                        // ディレクトリのパスの移動
    if (mkdir($dirName, $mode) !== true) {
        return false;
    }

    return true;

}

// mkdir
// 

/* 
 * バリデートチェック
 * 引数：   elmName:all, mode:string
 * 概要：   渡された第一引数に対し、modeで指定した種類のバリデートチェックを行う。
 */
function Validate($elmName, $mode, $option=null) {
    $ret = true;
    switch ($mode) {
        case 'set':
        $ret = IsValidateCheck($elmName);
        break;
        case 'null':
        $ret = NotNullCheck($elmName);
        break;
        case 'string':
        $ret = StringCheck($elmName, $option);
        break;
        case 'type':
        $ret = TypeCheck($elmName, $option);
        break;
        case 'fileName':
        $ret = FileNameCheck($elmName, $opition);
        default:
        break;
    }
    return $ret;
}

/* 
 * NULLチェック
 * 引数：   elmName:all
 * 概要：   渡された第一引数に対し、NULLでないか判定を行う。
 * 戻り値： NULLならfalse, NULLでなければtrue.
 * 備考：   型チェックは行わない。issetではなく、is_nullで判定を行う。(issetはisValidateCheck関数で行う。)
 * 作成日： 2018/03/25
 */
function NotNullCheck($elmName) {
    $ret = true;
    if (is_null($elmName)) {
        $ret = false;
    }
    return $ret;
}


/* 
 * 引数存在チェック
 * 引数：   elmName:all
 * 概要：   渡された第一引数に対し、引数が存在するか判定を行う。
 * 戻り値： NULLならfalse, NULLでなければtrue.
 * 備考：   型チェックは行わない。
 * 作成日： 2018/03/25
 */
function IsValidateCheck($elmName) {
    $ret = true;
    if (!isset($elmName)) {
        $ret = false;
    }
    return $ret;
}

/* 
 * 文字チェック
 * 引数：   elmName:string, str:string
 * 概要：   渡された第一引数と第二引数が同一か判定する。
 * 戻り値： 同一でなければfalse, 同一であればtrue.
 * 備考：   チェックレベルの導入は後日行う予定。
 * 作成日： 2018/03/25
 */
function StringCheck($elmName, $str) {
    $ret = true;
    if ($elmName !== $str) {
        $ret = false;
    }
    return $ret;
}
