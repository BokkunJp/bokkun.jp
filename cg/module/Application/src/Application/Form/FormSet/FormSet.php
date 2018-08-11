<?php

namespace Application\Form\FormSet;

use Zend\Form\Form;

class FormSet extends Form {
    public function __construct() {
        $this->form = new Form('template');
    }
}