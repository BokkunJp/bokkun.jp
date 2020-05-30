<form action='./{$base}/server.php' method='POST'>
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

    <button type='button' name="edit" value="select">ソースを選択する</button>
</form>

<span name='test'></span>