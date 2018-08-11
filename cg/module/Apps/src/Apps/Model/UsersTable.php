<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Apps\Model;

// 使用するモデル
use \Apps\Model\BaseMethod;

// テーブルオブジェクト(非公開)
class UsersTable extends BaseMethod
{       
    // 全てのデータを取得
    public function fetchAll() {
        $ret = $this->tableGateway->select();
        return $ret;
    }
    
    // IDが存在するかどうかチェックする
    public function getID($id) {
        $data = $this->getUserById($id);
        if ($data === false) {
            return false;
        } else if ($data->id < 0) {
            return false;
        }
        return true;
    }
    
    // IDからデータを取得
    public function getUserById($id) {
        $id = (int)$id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
//        if (!$row) {
//            throw new \Exception("Could not find row!!");
//        }
        
        
        return $row;
    }
    
    // 入力したデータを保存
    public function saveUser(Users $users) {
        $data = array(
            'id' => $users->id,
            'name' => $users->name,
            'email' => $users->email
            
        );
        $id = (int)$users->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if($this->getUser($id)) {
             $this->tableGateway->update($data, array('id' => $id));   
            } else {
                throw new \Exception('Form is does not exist');
            }
        }
    }
}
