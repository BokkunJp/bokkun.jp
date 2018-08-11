<?php
$post = $_POST;
if (!empty($post['val'])) {
   $val = intval($post['val']);
}
?>
<!-- サンプルソース用HTML -->
<form method='POST' action=''>
    ID: <input type='number' class='id' name='id' min=0 />
    登録する値： <input type='number' class='val' name='val' min=0 /> <br/>
    <button type='submit' class='view' name='view' onclick='View()'>閲覧</button>
    <button type='submit' class='save' name='save' onclick='Save();'>登録</button>
    <button type='submit' class='upd' name='upd' onclick='Update();'>更新</button>
    <button type='submit' class='dlt' name='dlt' onclick='Delete();'>削除</button>
</form>

        データ一覧
<table border="1">
    <tr>
        <th>ID</th>
        <th>Value</th>
        <th>登録日</th>
        <th>登録日時</th>
        <th>更新日</th>
        <th>更新日時</th>
    </tr>

    <!--閲覧-->
    <?php
    if (!empty($post['view'])) {
        if  (!empty($post['id'])) {
            echo 'ID '. $post['id']. 'の値をセレクト！<br/>';
            $select = $db->Select($post['id']);
        } else {
            $select = $db->SelectAll();
        }
    } else {
         $select = $db->SelectAll();
    }
    if (is_null($select)) {
        echo SELECT_ERROR;
        return -1;
    }
        $i = 0;
        foreach ($select as $value) {
            echo "<tr><td>{$value['id']}</td>
            <td>{$value['val']}</td>
            <td>{$value['installday']}</td>
            <td>{$value['installtime']}</td>
            <td>{$value['updateday']}</td>
            <td>{$value['updatetime']}</td></tr>";
        }
    ?>

    <!--登録-->
    <?php
    if (!empty($post['save']) && isset($post['save'])) {
        $db->Insert($post['val']);
    } else {
        if (isset($post['save'])) {
            echo SAVE_ERROR;
            return -1;
        }
    }

    ?>
    <!--更新-->
    <?php
    if (!empty($post['upd']) && isset($post['upd'])) {
        $db->Update($post['id'], $post['val']);
    } else {
        if (isset($post['upd'])) {
            echo UPDATE_ERROR;
            return -1;
        }
    }
    ?>

    <!--削除-->
    <?php
    if (!empty($post['dlt']) && isset($post['dlt'])) {
        if (isset($post['id']) && !empty($post['id'])) {
            $db->Delete($post['id']);
        } else {
            $db->DeleteAll();
        }
    } else {
        if (isset($post['dlt'])) {
            echo DELETE_ERROR;
            return -1;
        }
    }
    ?>
</table>
