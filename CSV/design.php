<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php

use BasicTag\ScriptClass;

$posts = PublicSetting\Setting::GetPosts();
$session = new PublicSetting\Session();

if (isset($posts['csv']) && $posts['csv'] === 'make') {
    $alert = new ScriptClass();

    if (isset($posts['send'])) {
        $alert->Alert('CSVを作成します。');
        $inputFlg = true;
    } else if (isset($posts['view'])) {
        $inputFlg = false;
    }

    Main($inputFlg);
}
?>
<form method='POST'>
    <table>
        <caption>
            <h2>CSV作成／データ追加・変更</h2>
        </caption>
        <thead>
            <tr>
                <th>
                    ファイル名
                </th>
                <td>
                    <input type='text' name='file-name' />
                </td>

                <th>
                    列番号
                </th>
                <td>
                    <input type='text' name='col-number' />
                </td>
            </tr>
            <tr>
                <th>要素x</th>
                <th>要素y</th>
                <th>要素z</th>
            </tr>
        </thead>
        <tbody>
            <td>
                <input type='text' name='x-value' />
            </td>
            <td>
                <input type='text' name='y-value' />
            </td>
            <td>
                <input type='text' name='z-value' />
            </td>
        </tbody>
    </table>
    <input type='hidden' name='csv' value="make" />
    <input type='hidden' name='token' value="<?= $token = MakeToken() ?>" />
    <button type='submit' name='send' value='true'>データを送信</button>
    <button type='submit' name='view' value='true'>データを表示</button>
</form>
<?php
$csvData = $session->Read('csv');
if (empty($csvData)) {
    $csvData['header'] = null;
    $csvData['row'] = null;
}
?>
<div name='output'>
    <h2>CSV表示</h2>
    <span class='header'><?= $csvData['header'] ?></span> <br/>
    <span class='row'><?= $csvData['row'] ?></span>
</div>

<?php
$filePath = AddPath(PUBLIC_CSV_DIR, basename(__DIR__));
$fileArray = IncludeFiles($filePath, 'csv', true);
$base = new PublicSetting\Setting();

// 次期改修
//$downloadHtml = new CustomTagCreate();
//$downloadHtml->SetHref('test', 'download', 'csv', false, "download");
//$downloadHtml->ExecTag(true);
echo "<p>";
foreach ($fileArray as $_value) {
    $_filePath = AddPath($base->GetUrl(basename(__DIR__), 'csv'), $_value, false);
    echo "<a href=\"{$_filePath}\" download>{$_value}ダウンロード</a> <br/>";
}
echo "</p>";

$session->Delete('csv');
SetToken($token);