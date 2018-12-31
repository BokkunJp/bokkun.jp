<?php
/* Smarty version 3.1.30, created on 2018-12-01 15:02:27
  from "C:\Users\bokku\Documents\Project\bokkun.jp.project\private\create\index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5c02947374d233_69450982',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '98c9ff02ee464ae25f3205a85526a61a6a12378d' => 
    array (
      0 => 'C:\\Users\\bokku\\Documents\\Project\\bokkun.jp.project\\private\\create\\index.tpl',
      1 => 1534098063,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c02947374d233_69450982 (Smarty_Internal_Template $_smarty_tpl) {
?>
<form action='./<?php echo $_smarty_tpl->tpl_vars['base']->value;?>
/server.php' method='POST'>
  <div><p2>新規作成</p2></div>
  <input type='radio' name='type' value='default' <?php if (isset($_smarty_tpl->tpl_vars['session']->value['type'])) {?> <?php if ($_smarty_tpl->tpl_vars['session']->value['type'] == 'default') {?>checked = "cehcked"<?php }
}?> /> デフォルト
  <input type='radio' name='type' value='custom' <?php if (isset($_smarty_tpl->tpl_vars['session']->value['type'])) {?> <?php if ($_smarty_tpl->tpl_vars['session']->value['type'] == 'custom') {?>checked = "cehcked"<?php }
}?> /> カスタマイズ
  <p>使用するテンプレートエンジン<br/>
     smarty <input type='radio' name='use_template_engine' value='smarty' <?php if (isset($_smarty_tpl->tpl_vars['session']->value['use_template_engine'])) {?> <?php if ($_smarty_tpl->tpl_vars['session']->value['use_template_engine'] == 'on') {?>checked = "cehcked"<?php }
}?> />
     twig <input type='radio' name='use_template_engine' value='twig' <?php if (isset($_smarty_tpl->tpl_vars['session']->value['use_template_engine'])) {?> <?php if ($_smarty_tpl->tpl_vars['session']->value['use_template_engine'] == 'on') {?>checked = "cehcked"<?php }
}?> />
     使わない <input type='radio' name='use_template_engine' value='off' <?php if (isset($_smarty_tpl->tpl_vars['session']->value['use_template_engine'])) {?> <?php if ($_smarty_tpl->tpl_vars['session']->value['use_template_engine'] == 'off') {?>checked = "cehcked"<?php }
}?> /></p>
タイトル： <input type='textbox' name='title' <?php if (isset($_smarty_tpl->tpl_vars['session']->value['title'])) {?> <?php if (!empty($_smarty_tpl->tpl_vars['session']->value['title'])) {?>value=<?php echo $_smarty_tpl->tpl_vars['session']->value['title'];
}
}?> />
<button type='submit' id='create'>ページの新規作成</button>
</form>
<br />
<form action='./<?php echo $_smarty_tpl->tpl_vars['base']->value;?>
/edit.php' method='POST'>
    <div><p2>編集</p2></div>
    <div class="warning">本機能は現在鋭意実装中です。</div>
    <select name='select'>
      <?php $_smarty_tpl->_assignInScope('count', 0);
?>
      <?php $_smarty_tpl->_assignInScope('max', count($_smarty_tpl->tpl_vars['dir']->value));
?>
      <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['dir']->value, 'item_name');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['item_name']->value) {
?>
        <?php if (mb_strpos($_smarty_tpl->tpl_vars['item_name']->value,'.') !== 0 && mb_strpos($_smarty_tpl->tpl_vars['item_name']->value,'template') !== 0 && mb_strpos($_smarty_tpl->tpl_vars['item_name']->value,'common') !== 0 && mb_strpos($_smarty_tpl->tpl_vars['item_name']->value,'index.php') !== 0 && mb_strpos($_smarty_tpl->tpl_vars['item_name']->value,'client') !== 0) {?>
              <option value='<?php echo $_smarty_tpl->tpl_vars['item_name']->value;?>
' ><?php echo $_smarty_tpl->tpl_vars['item_name']->value;?>
</option>
          <?php }?>
      <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

    </select>

    <button type='submit' name="delete" value="delete">削除する</button>
    タイトル(編集用)： <input type='textbox' name='title' <?php if (isset($_smarty_tpl->tpl_vars['session']->value['title'])) {?> <?php if (!empty($_smarty_tpl->tpl_vars['session']->value['title'])) {?>value=<?php echo $_smarty_tpl->tpl_vars['session']->value['title'];
}
}?> />
    <button type='submit' name="edit" value="edit">編集する</button>
</form>
<?php }
}
