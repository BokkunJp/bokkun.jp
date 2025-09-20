<!-- Server.phpのクラス化 -->
<?php
class Admin
{
    private $admin;
    private $use;
    private $basePath;
    private $session;
    private $post;

    public function __construct()
    {
        require_once dirname(dirname(__DIR__, 2)). '/private/common/Function/Tag.php';
        $this->initialize();
    }
    public function initialize()
    {
        $this->admin = new adminClass();

        $this->adminPath = dirname(__DIR__);
        $this->basePath = dirname(dirname(__DIR__, 2));

        $this->session = filter_input_array(INPUT_SESSION);
        $this->post = filter_input_array(INPUT_POST);
        $judge = array();
        foreach ($this->post as $post_key => $post_value) {
            $$post_key = $post_value;
            $judge[$$post_key] = $post_value;
        }
    }
    public function copyDir($pathName='', $fileName='', $modifer='')
    {
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
        $this->admin->alert('ページを作成しました。');
        session_destroy();
    }
}
