<form action='./{$base}/server.php' method='POST'>
    <div><p2><h3>エラーログ選択</h3></p2></div>
    <select class='set_log' id='error' name='error_log'>
      {foreach from=$dir item=item_name}
          <option value='{$item_name}' >{$item_name}</option>
      {/foreach}
    </select>
    <select class='select_log' id='error_src' name='select_log'>
    </select>

    <button type='button' name="edit" value="select">ログを選択する</button>
  <div class='result'>
    <p><h3>選択したエラーログの内容</h3></p>
    <pre class='log'><span class='result-log-view'></span></pre>
  </div>
</form>
