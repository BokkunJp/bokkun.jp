<?php
namespace App\Controller\Component;
use Cake\Validation\Validator;
use Cake\Controller\Component;

class ValidateComponent extends Component {
  protected $validator;
  function __construct() {
    $this->validator = new Validator();
  }
  public function InputValidate() {
    $this->validator->notEmpty('ImageName', __('値が入力されていません'))
              ->add('ImageName',
    'length',  ['rule' => ['maxLength', 5],
      'message' => __('最大文字数を超えています')])
      ->add('ImageFile',['rule' => [
      'uploadedFile', ['optional' => true]],
      ]);
      return $this->validator;

  }
  public function ExistArrayValue(array $data) {
    if (empty($data)) {
      return false;
    }

    foreach ($data as $_key => $_val) {
      if (empty($_val)) {
        return false;
      }
    }

    return true;
  }
}
