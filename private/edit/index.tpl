<form action='./{$base}/server.php' method='POST'>
    <div><p2>編集</p2></div>
    <select name='select'>
      {$count=0}
      {$max=count($dir)}
      {foreach from=$dir item=item_name}
        {if mb_strpos($item_name, '.')!==0 && mb_strpos($item_name, 'template')!==0 && mb_strpos($item_name, 'common')!==0&& mb_strpos($item_name, 'index.php')!==0&& mb_strpos($item_name, 'client')!==0}
              <option value='{$item_name}' >{$item_name}</option>
          {/if}
      {/foreach}
    </select>
    <select class='select_directory' id='select_directory' name='select_directory'></select>
    <select class='select_file' id='select_file' name='select_file'></select>

    <button type='button' name="edit" class="edit" value="edit">ソースを選択する</button>

  <div class='result'>
    <textarea class='result-src'></textarea>
    <p><h3>現在のソース：</h3></p>
    <p><span class='result-src-view'></span></p>
    <button type='button' name="save">ソースを保存する</button>
  </div>
{$editSrcToken->getTag()}
</form>
