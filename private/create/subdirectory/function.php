<!-- Server.phpのクラス化 -->
<?php
class Admin {
    private $adminError;
    private $use;
    private $basePath;
    private $session;
    private $post;

    public function __construct() {
        require_once dirname(dirname(dirname(__DIR__))). '/public/common/Function/Tag.php';
        $this->Initialize();
    }
    public function Initialize() {
        $this->adminError = new AdminError();
        $this->use = new UseClass();

        $this->adminPath = dirname(__DIR__);
        $this->basePath = dirname(dirname(dirname(__DIR__)));

        session_start();
        $this->session = $_SESSION;
        $this->post = $_POST;
        $judge = array();
        foreach ($post as $post_key => $post_value) {
            $$post_key = $post_value;
            $judge[$$post_key] = $post_value;
        }
    }
    public function DirCopy($pathName='', $fileName='', $modifer='') {
        copy("$baseFileName/$fileName.$_pathList", "$title/$fileName.$_pathList");            // フォルダ内のindex.php作成
        if ($_pathList === 'php') {
            var_dump("/$baseFileName/design.php");
            copy("$baseFileName/design.php", "$title/design.php");          // フォルダ内のdesgin.php作成
            if ($use_smarty === 'on') {
                copy("$baseFileName/index.tpl", "$title/index.tpl");        // smarty設定時、index.tpl作成
            } else {
                mkdir("$title/subdirectory");                               // smarty未設定時、subdirectoryディレクトリ作成
            }
        }
        $use->Alert('ページを作成しました。');
        session_destroy();
        }
}

class AdminError {
    protected $use;
    public function __construct() {
        $this->use = new PrivateTag\UseClass();
    }

    public function UserError($message) {
        $this->use->Alert($message);
        $this->use->BackAdmin('create');
        exit;
    }

    public function Maintenance() {
        $this->UserError('メンテナンス中です。しばらくお待ちください。');
    }
}
