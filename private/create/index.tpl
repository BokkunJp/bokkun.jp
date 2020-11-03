<form action='./{$base}/server.php' method='POST'>
  <div><p2>新規作成</p2></div>
  <input type='radio' name='type' value='default' {if isset($smarty.session.type) } {if $smarty.session.type == 'default'}checked = "cehcked"{/if}{/if} /> デフォルト
  <input type='radio' name='type' value='custom' {if isset($smarty.session.type) } {if $smarty.session.type == 'custom'}checked = "cehcked"{/if}{/if} /> カスタマイズ
  <p>使用するテンプレートエンジン<br/>
     smarty <input type='radio' name='use_template_engine' value='smarty' {if isset($smarty.session.use_template_engine) } {if $smarty.session.use_tezmplate_engine == 'on'}checked = "cehcked"{/if}{/if} />
     twig <input type='radio' name='use_template_engine' value='twig' {if isset($smarty.session.use_template_engine) } {if $smarty.session.use_template_engine == 'on'}checked = "cehcked"{/if}{/if} />
     使わない <input type='radio' name='use_template_engine' value='off' {if isset($smarty.session.use_template_engine) } {if $smarty.session.use_template_engine == 'off'}checked = "cehcked"{/if}{/if} /></p>
タイトル： <input type='textbox' name='title' {if isset($smarty.session.title) } {if !empty($smarty.session.title)}value={$smarty.session.title}{/if}{/if} />
<button type='submit' id='create'>ページの新規作成</button>
  <input type='hidden' name='token' value={$token} />
</form>
<br />
<form action='./{$base}/edit.php' method='POST'>
    <div><p2>編集</p2></div>
    <div class="warning">本機能は現在鋭意実装中です。</div>
    <select name='select'>
      {$count=0}
      {$max=count($dir)}
      {foreach from=$dir item=item_name}
              <option value='{$item_name}' >{$item_name}</option>
      {/foreach}
    </select>

    <button type='submit' name="delete" value="delete">削除する</button>
    <button type='submit' name="copy"" value="copy">複製する</button>
    タイトル(編集・複製用)： <input type='textbox' name='title' {if isset($smarty.session.title) } {if !empty($smarty.session.title)}value={$smarty.session.title}{/if}{/if} />
    <button type='submit' name="edit" value="edit">編集する</button>
  <input type='hidden' name='token' value={$token} />
</form>
