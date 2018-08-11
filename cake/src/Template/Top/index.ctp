<?=$this->fetch('css') ?>
<?php
echo $this->Html->css($controller. '/design');
?>
<h1>今日の勉強内容</h1>
<p>フォームの送信(POST)</p>
<?=$this->Form->create(null, ['type'=>'post', 'url'=>['controller'=>$controller, 'action'=>'send']]) ?>
<?=$this->Form->text($controller) ?>
<?=$this->Form->submit('Set') ?>
