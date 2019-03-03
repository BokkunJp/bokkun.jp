<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Hash;

define ('LENG_MAX', 256);
define ('ID_MAXLENG', 32);
define('PASS_MAXLENG', 32);

class AuthComponent extends BaseComponent {
    protected function CheckBase($dataName, $lengMax= LENG_MAX) {
        $post_data = $this->request->getData($dataName);
        $ret = ['evidence' => '', 'judge' => true];

        if (!isset($post_data) || is_null($post_data)) {
            $ret['evidence'] = 'post Error';
            $ret['judge'] = false;
        }

        if (!is_numeric($post_data)) {
            $ret['evidence'] = 'numeric Error';
            $ret['judge'] = false;
        }

        if (count((int)$post_data) > ID_MAXLENG) {
            $ret['evidence'] = 'value Error';
            $ret['judge'] = false;
        }

        if ($ret === false) {
            debug($post_data);
        }

        return true;
    }

    // IDチェック
    public function IdCheck() {
        return $this->CheckBase('login_id', ID_MAXLENG);
     }

    // PWチェック
    public function PasswordCheck() {
        $ret = $this->CheckBase('login_password', PASS_LENGTH);
     }
}