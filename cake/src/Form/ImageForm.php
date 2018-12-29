<?php
namespace App\Form;

use Cake\Form\Form;                 // Formヘルパー
use Cake\Form\Schema;              // スキーマ
use Cake\Validation\Validator;    // バリデータ
class ImageForm extends Form {
  protected function _buildSchema(Schema $schema) {
    $schema->addField('ImageName', ['type' => 'String', 'length' => 10])
    ->addField('ImageFile', ['name' => 'file', 'type' => 'file']);
    return $schema;
  }

  protected function _buildValidator(Validator $validator) {
    $validator
    ->notEmpty('ImageName', __('値が入力されていません'))
    ->notEmpty('ImageFile', __('ファイルがアップロードされていません'))
    ->add('ImageName',
    'length',  ['rule' => ['maxLength', 5],
      'message' => __('最大文字数を超えています')]
    );

    return $validator;
  }

  protected function _execute(array $data) {
    return true;
  }
}
