<?=$this->fetch('css') ?>
<?php
echo $this->Html->css('HomePage/'. $controller. '/design');
?>
<?=$this->Form->create(null, ['type'=>'post', 'url'=>['controller'=>$controller, 'action'=>'send']]) ?>
<?=$this->Form->text($controller) ?>
<?=$this->Form->create('File', ['type' => 'file', 'enctype' => 'multipart/form-data', 'url'=>['controller'=>$controller, 'action'=>'send']]); ?>
<?=$this->Form->file('file') ?>
<div class='footer_char'>※同じ名前のファイルは複数保存されず、上書きされます</div> <?=$this->Form->submit('送信する') ?>
