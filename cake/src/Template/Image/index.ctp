<?=$this->fetch('css') ?>
<?php
echo $this->Html->css('HomePage/'. $controller. '/design');
?>
<?=$this->Form->create($form, $validate == false?[]:['type'=>'post', 'url'=>['controller'=>$controller, 'action'=>'send']]) ?>
<?=$this->Form->control($controller. 'Name', ['label' => 'ファイル名', 'required' => 'false']) ?>
<?=$this->Form->control($controller. 'File', ['type' => 'file', 'label' => '', 'enctype' => 'multipart/form-data', 'url'=>['controller'=>$controller, 'action'=>'send'], 'required' => 'false']) ?>
<div class='footer_char'>※同じ名前のファイルは複数保存されず、上書きされます</div>
<?=$this->Form->submit('画像を登録する') ?>
<?=$this->Form->end() ?>
