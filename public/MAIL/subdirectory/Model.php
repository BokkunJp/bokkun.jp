<?php
namespace Model;
require_once 'UI.php';

use PublicSetting\Session as Session;
class Mail {
   private $address, $title, $body;
   private $flg;

    function __construct() {
        unset($session);
        $session = new Session();
   }

   protected function Init() {
       $this->address=$title=$body=null;
       $this->flg = true;
   }

   public function SetAddress($data) {
     $this->Init();
     $this->address = $data['email'];
     $this->title = $data['title'];
     $this->body = $data['contents'];
     var_dump($this);
   }

   public function SendMail() {
     if (!$this->IsValid()) {
       return false;
     }
   }

   public function CheckDataType($aryData) {
     if (!is_array($aryData) || count($aryData) !== 3) {
       return false;
     }

     foreach ($aryData as $_key => $_value) {
      var_dump($_key);
      switch ($_key) {
        case 'email':
        break;
        case 'title':
        break;
        case 'contents':
        break;
        default:
        return false;
        break;
      }
     }

     return true;

   }

   public function IsValid() {
       $this->isValidAddress();
    //    $this->IsValidTitle();
    //    $this->IsValidBody();

        return $this->flg;
   }

   private function isValidAddress() {
      var_dump(preg_match('/^[a-zA-z0-9]+[a-zA-z0-9\.]*@[a-zA-z0-9\.][a-zA-z0-9]$/', $this->address));
   }
}
