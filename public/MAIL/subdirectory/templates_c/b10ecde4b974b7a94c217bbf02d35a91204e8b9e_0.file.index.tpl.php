<?php
/* Smarty version 3.1.30, created on 2018-02-26 03:13:33
  from "/virtual/bokkun/public_html/bokkun.xyz/public/MAIL/index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_5a92fccd878f84_26390180',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b10ecde4b974b7a94c217bbf02d35a91204e8b9e' => 
    array (
      0 => '/virtual/bokkun/public_html/bokkun.xyz/public/MAIL/index.tpl',
      1 => 1519536183,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a92fccd878f84_26390180 (Smarty_Internal_Template $_smarty_tpl) {
?>

<form method='POST' action='<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['url']->value, ENT_QUOTES, 'UTF-8', true);?>
'>
<ul>
<li><slian class='label'>送信先アドレス：</slian> <input class='form' tylie='email' /></li>
<li><slian class='label'>タイトル：</slian> <input class='form' tylie='textbox' max=32 /></li>
<li><slian class='label'>本文：</slian> <textarea max=256 placeholder="メール本文を入力" /></textarea></li>
<li><button tylie='submit'>送信する</button></li>
</ul>
</form>
<?php }
}
