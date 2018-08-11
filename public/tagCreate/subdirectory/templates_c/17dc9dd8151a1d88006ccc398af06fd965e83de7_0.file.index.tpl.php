<?php
/* Smarty version 3.1.32, created on 2018-07-16 05:53:27
  from '/virtual/bokkun/public_html/bokkun.jp/public/tagCreate/index.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.32',
  'unifunc' => 'content_5b4bb4471eda17_59300004',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '17dc9dd8151a1d88006ccc398af06fd965e83de7' => 
    array (
      0 => '/virtual/bokkun/public_html/bokkun.jp/public/tagCreate/index.tpl',
      1 => 1499626311,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5b4bb4471eda17_59300004 (Smarty_Internal_Template $_smarty_tpl) {
?>ようこそ、<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
さん。

<div class='input'>
    <span class='input'><textarea class='infoInput' placeholder='タグ情報を入力してください'></textarea></span>
</div>

<div class='selectTag'>
    <span class='input'>
        作成するタグを選択してください。
        <?php echo $_smarty_tpl->tpl_vars['inputSelect']->value;?>

    </span>
    
    <button class='tagCreateButton'>タグを作成する</button>
</div>
    
<div class='createResult'>
    タグを作成しました。
    <button class='enable'>タグを表示する</button>
    <button class='disable'>タグを表示しない</button>
    
    <div class='result'>aa</div>
</div>

    <div class='scroll'>
        <table>
            <tr>
                1
                <th>
                <td>1-1</td>
                <td>1-2</td>
                <td>1-3</td>
                <td>1-4</td>
                </th>
            </tr>
        </table>
    </div>
    <?php }
}
