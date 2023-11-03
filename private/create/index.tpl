<form action='./{$base}/server.php' method='POST'>
  <div><h2>新規作成</h2></div>
  <p>デザイン</p>
  <label class='input-design'><input type='radio' name='type' value='default' {if isset($admin_addition.type) } {if $admin_addition.type == 'default'}checked = "cehcked"{/if}{/if} /> デフォルト</label>
  <label class='input-design'><input type='radio' name='type' value='custom' {if isset($admin_addition.type) } {if $admin_addition.type == 'custom'}checked = "cehcked"{/if}{/if} /> カスタマイズ</label>
  <label class='input-design'><input type='radio' name='type' value='scratch' {if isset($admin_addition.type) } {if $admin_addition.type == 'scratch'}checked = "cehcked"{/if}{/if} /> スクラッチ</label>
  <p>テンプレートエンジン</p>
     <label class='input-template'>smarty <input type='radio' name='use_template_engine' value='smarty' {if isset($admin_addition.use_template_engine) } {if $admin_addition.use_template_engine == 'smarty'}checked = "cehcked"{/if}{/if} /></label>
     <label class='input-template'>twig <input type='radio' name='use_template_engine' value='twig' {if isset($admin_addition.use_template_engine) } {if $admin_addition.use_template_engine == 'twig'}checked = "cehcked"{/if}{/if} /></label>
     <label class='input-template'>使わない <input type='radio' name='use_template_engine' value='off'{if isset($admin_addition.use_template_engine) }{if $admin_addition.use_template_engine == 'off'}checked="cehcked" {/if}{/if} /></label>
  <p>タイトル</p>
     <input type='textbox' name='title' {if isset($admin_addition.title) } {if !empty($admin_addition.title)}value={$admin_addition.title}{/if}{/if} />
<button type='submit' id='create'>ページの新規作成</button>
{$createToken->getTag()}
</form>
<br />
<form action='./{$base}/edit.php' method='POST'>
    <div>
        <h2>削除・複製</h2></div>
    {* <div><span><input type='checkbox'' name='all-copy' value='true' />すべてコピーする</span></div> *}
    <select name='select'>
      {$count=0}
      {$max=count($dir)}
      {foreach from=$dir item=item_name}
              <option value='{$item_name}' >{$item_name}</option>
      {/foreach}
    </select>

    <button type='submit' name="delete" value="delete">削除する</button>
タイトル(複製・編集用)： <input type='textbox' name='copy_title' {if isset($admin_addition.copy_title) }
{if !empty($admin_addition.copy_title)}value={$admin_addition.copy_title}{/if}{/if} />
    <button type='submit' name="copy"" value="copy">複製する</button>
    {* <button type='submit' name="edit" value="edit">編集する</button> *}
    {$editToken->getTag()}
</form>
