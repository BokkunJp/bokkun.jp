<form action='./{$base}/server.php' method='POST'>
  <div><h2>新規作成</h2></div>
  <p>デザイン</p>
  <input type='radio' name='type' value='default' {if isset($smarty.session.type) } {if $smarty.session.type == 'default'}checked = "cehcked"{/if}{/if} /> デフォルト
  <input type='radio' name='type' value='custom' {if isset($smarty.session.type) } {if $smarty.session.type == 'custom'}checked = "cehcked"{/if}{/if} /> カスタマイズ
  <input type='radio' name='type' value='scratch' {if isset($smarty.session.type) } {if $smarty.session.type == 'custom'}checked = "cehcked"{/if}{/if} /> スクラッチ
  <p>テンプレートエンジン</p>
     smarty <input type='radio' name='use_template_engine' value='smarty' {if isset($smarty.session.use_template_engine) } {if $smarty.session.use_tezmplate_engine == 'on'}checked = "cehcked"{/if}{/if} />
     twig <input type='radio' name='use_template_engine' value='twig' {if isset($smarty.session.use_template_engine) } {if $smarty.session.use_template_engine == 'on'}checked = "cehcked"{/if}{/if} />
     使わない <input type='radio' name='use_template_engine' value='off' {if isset($smarty.session.use_template_engine) } {if $smarty.session.use_template_engine == 'off'}checked = "cehcked"{/if}{/if} /></p>
  <p>タイトル</p>
     <input type='textbox' name='title' {if isset($smarty.session.title) } {if !empty($smarty.session.title)}value={$smarty.session.title}{/if}{/if} />
<button type='submit' id='create'>ページの新規作成</button>
  <input type='hidden' name='token' value={$token} />
</form>
<br />
<form action='./{$base}/edit.php' method='POST'>
    <div><h2>複製・削除</h2></div>
    {* <div><span><input type='checkbox'' name='all-copy' value='true' />すべてコピーする</span></div> *}
    <select name='select'>
      {$count=0}
      {$max=count($dir)}
      {foreach from=$dir item=item_name}
              <option value='{$item_name}' >{$item_name}</option>
      {/foreach}
    </select>

    <button type='submit' name="delete" value="delete">削除する</button>
    <button type='submit' name="copy"" value="copy">複製する</button>
    {* タイトル(編集・複製用)： <input type='textbox' name='title' {if isset($smarty.session.title) } {if !empty($smarty.session.title)}value={$smarty.session.title}{/if}{/if} />
    <button type='submit' name="edit" value="edit">編集する</button> *}
  <input type='hidden' name='token' value={$token} />
</form>
