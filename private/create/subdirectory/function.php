<!-- Server.phpのクラス化 -->
<?php
namespace Admin;
require_once dirname(dirname(dirname(__DIR__))). '/public/common/Function/Tag.php';
class File {
  public $path;
  public $name;
  public $extention;
}
class Controller {
    private $adminError;
    private $use;
    private $basePath;
    private $session;
    private $post;

    public function __construct() {
        $this->Initialize();
    }
    private function Initialize() {
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
  }

  class Model extends Base implements Interface\ModelInterface {
    /*
     * 必要なプロパティの検証
     * モードに応じて、エラー文言なども変える
    */
    private function Validate($mode) {

      switch ($mode) {
        case 'add':
        break;
        case 'edit':
        break;
        case 'delete':
        break;
      }


  }

  class Base {
    public static MAXLENGTH = 32;
    protected function StringCheck($string) {
      if (preg_match('/^[a-zA-Z][a-zA-Z+-_]*/', $string) === 0) {
          return false;
      }
      return true;
    }

    protected function StringValueCheck($string) {
        if (strlen($title) > self::MAX_LENGTH) {
             return false;
        }
        return true;
    }

    protected function MakeDir(File $file) {
        return mkdir($file->path/$newDir->filename);
    }

    protected function CopyFile(File $source, File $dest) {
        return copy("$source->path/$source->name.$source->extenstion", "$dest->path/$dest->name.$dest->extenstion");
    }

    protected function Rename(File $object, String $newFileName) {
        return rename($object->name, $newFileName);
    }

    protected function DeleteFile(File $file) {
        return unlink($file->path/$file->filename.$file->extenstion);
    }
  }

class AdminError {
    protected $use;
    public function __construct() {
        $this->use = new UseClass();
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
