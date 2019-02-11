<?php
namespace App\Controller\Component;
use \Cake\Filesystem\Folder;
use \Cake\Filesystem\File;
use Cake\Controller\Component;

class FileComponent extends Component {
  public function Test() {
    $file = new File(WWW_ROOT);
    debug(TMP);
  }
}
