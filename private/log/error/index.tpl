<form action='./{$base}/server.php' method='POST'>
    <div><p2>編集</p2></div>
    <div class="warning">本機能は現在鋭意実装中です。</div>
    <select class='set_log' id='error' name='error_log'>
      {$count=0}
      {$max=count($dir)}
      {foreach from=$dir item=item_name}
        {if mb_strpos($item_name, '.')!==0 && mb_strpos($item_name, '..')!==0}
              <option value='{$item_name}' >{$item_name}</option>
          {/if}
      {/foreach}
    </select>
    <select class='select_log' id='error_src' name='select_log'>
    </select>

    <button type='button' name="edit" value="select">ログを選択する</button>
  <div class='result'>
    <p><h3>選択したエラーログ：</h3></p>
    <p><span class='result-log-view'></span></p>
  </div>
</form>
