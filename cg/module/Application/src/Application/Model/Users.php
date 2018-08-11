<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

class Users {
    public $id;
    public $name;
    public $email;
        
    public function exchangeArray($data) {
        $this->id = (isset($data['id'])) ? $data['id'] : 0;
        $this->name = (isset($data['id'])) ? $data['name'] : '';
        $this->email = (isset($data['id'])) ? $data['email'] : '';
        
    }
    public function DefaultSet() {
        $this->id = 0;
        $this->name = 'default';
        $this->email = 'This is message.';
    }
}