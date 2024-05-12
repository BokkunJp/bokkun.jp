<form action='./{$base}/server.php' method='POST'>
    <div><p2><h3>アクセスログ選択</h3></p2></div>
    <select class='set_log' id='access' name='access_log'>
      {foreach from=$dir item=item_name}
          <option value='{$item_name}' >{$item_name}</option>
      {/foreach}
    </select>

    <button type='button' name="edit" value="select">ログを選択する</button>
  <div class='result'>
    <p><h3>選択したアクセスログの内容</h3></p>
    <pre class='log'><span class='result-log-view'></span></pre>
  </div>
</form>
