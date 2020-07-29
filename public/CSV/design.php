<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
$post = PublicSetting\Setting::GetPost('csv');
if ($post) {
    echo 'CSVを作成しました。';
}
?>
<form method='POST'>
    <table>
        <caption><h2>CSVデータ作成</h2></caption>
        <thead>
            <tr>
                <th>ファイル名：
                </th>
                <td>
                    <input type='text' name='file-name' />
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
    <input type='hidden' name='token' value="<?=MakeToken()?>" />
    <button type='submit'>データを送信</button>
</form>
<?php
$filePath = AddPath(PUBLIC_CSV_DIR, basename(__DIR__));
$fileArray = IncludeFiles($filePath, 'csv', true);
$base = new PublicSetting\Setting();

// 次期改修
//$downloadHtml = new CustomTagCreate();
//$downloadHtml->SetHref('test', 'download', 'csv', false, "download");
//$downloadHtml->ExecTag(true);
foreach ($fileArray as $_value) {
    $_filePath = AddPath($base->GetUrl(basename(__DIR__), 'csv'), $_value, false);
    echo "<a href=\"{$_filePath}\" download>{$_value}ダウンロード</a>";
}
?>


<?php
SetToken();