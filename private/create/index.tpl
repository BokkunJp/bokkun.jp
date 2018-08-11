<form action='./{$base}/server.php' method='POST'>
  <div><p2>新規作成</p2></div>
  <input type='radio' name='type' value='default' {if isset($session.type) } {if $session.type == 'default'}checked = "cehcked"{/if}{/if} /> デフォルト
  <input type='radio' name='type' value='custom' {if isset($session.type) } {if $session.type == 'custom'}checked = "cehcked"{/if}{/if} /> カスタマイズ
  <p>使用するテンプレートエンジン<br/>
     smarty <input type='radio' name='use_template_engine' value='smarty' {if isset($session.use_template_engine) } {if $session.use_template_engine == 'on'}checked = "cehcked"{/if}{/if} />
     twig <input type='radio' name='use_template_engine' value='twig' {if isset($session.use_template_engine) } {if $session.use_template_engine == 'on'}checked = "cehcked"{/if}{/if} />
     使わない <input type='radio' name='use_template_engine' value='off' {if isset($session.use_template_engine) } {if $session.use_template_engine == 'off'}checked = "cehcked"{/if}{/if} /></p>
タイトル： <input type='textbox' name='title' {if isset($session.title) } {if !empty($session.title)}value={$session.title}{/if}{/if} />
<button type='submit' id='create'>ページの新規作成</button>
</form>
<br />
<form action='./{$base}/edit.php' method='POST'>
    <div><p2>編集</p2></div>
    <div class="warning">本機能は現在鋭意実装中です。</div>
    <select name='select'>
      {$count=0}
      {$max=count($dir)}
      {foreach from=$dir item=item_name}
        {if mb_strpos($item_name, '.')!==0 && mb_strpos($item_name, 'template')!==0 && mb_strpos($item_name, 'common')!==0&& mb_strpos($item_name, 'index.php')!==0&& mb_strpos($item_name, 'client')!==0}
              <option value='{$item_name}' >{$item_name}</option>
          {/if}
      {/foreach}
    </select>

    <button type='submit' name="delete" value="delete">削除する</button>
    タイトル(編集用)： <input type='textbox' name='title' {if isset($session.title) } {if !empty($session.title)}value={$session.title}{/if}{/if} />
    <button type='submit' name="edit" value="edit">編集する</button>
</form>
