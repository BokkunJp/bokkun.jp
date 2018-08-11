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

   public function IsValid() {
    //    $this->isValidAddress();
    //    $this->IsValidTitle();
    //    $this->IsValidBody();

        return $this->flg;
   }
}

