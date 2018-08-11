<?php
namespace Application\Form\ElementSet;

use Zend\Form\Form;
use Zend\Form\Element;

class ElementSet extends Form {
    protected $serviceLocator;
    protected $element;
    
    public function __construct() {
        $this->element = new Element('template');
    }
    
    public function inputType($type) {
        switch ($type) {
            case 'button':
                break;
            case 'radio':
                break;
            case 'select':
                break;
            case 'text':
                break;
        }
    }
    
    public function setForm($lavel, $type, $value) {
        $this->setType($type);
        $this->setLabel($name);
        $this->setValue($value);
    }
    
    public function setMessages($messages) {
        $this->element->setMessages($messages);
    }
   
    
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
    
    public function getFormElement() {
        if (!isset($this->FormElement)) {
           $this->FormElement = $this->getServiceLocator()->get('Application\Form\ElementSet');
        }
        return $this->FormElement;

    }
}