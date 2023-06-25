<!-- デザイン用ファイル (PHPで処理を記述)-->
<?php
use BasicTag\ScriptClass;
use public\Setting as Setting;

if (!class_exists('Public\Token')) {
    $tokenPath = new \Path(PUBLIC_COMMON_DIR);
    $tokenPath->SetPathEnd();
    $tokenPath->Add('Token.php');
    require_once $tokenPath->Get();
}

$posts = public\Setting::GetPosts();
$csvToken = new Public\Token('csv-token', $session, true);

if (isset($posts['csv']) && $posts['csv'] === 'make') {
    $alert = new ScriptClass();

    if (isset($posts['send'])) {
        $alert->Alert('CSVを作成します。');
        $inputFlg = true;
    } elseif (isset($posts['view'])) {
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
    <?php $csvToken->Set(); ?>
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
$filePath = new \Path(PUBLIC_CSV_DIR);
$filePath->Add(basename(__DIR__));
$filePath = $filePath->Get();
// ディレクトリが存在しない場合は作成
if (!is_dir($filePath)) {
    mkdir($filePath, 0775, true);
}

$fileArray = IncludeFiles($filePath, 'csv', true);
$base = new Setting();

// 次期改修
//$downloadHtml = new CustomTagCreate();
//$downloadHtml->SetHref('test', 'download', 'csv', false, "download");
//$downloadHtml->ExecTag(true);
echo "<p>";
foreach ($fileArray as $_value) {
    $filePath = new \Path($base->GetUrl(basename(__DIR__), 'csv'));
    $filePath->Add($_value);
    echo "<a href=\"{$filePath->Get()}\" download>{$_value}ダウンロード</a> <br/>";
}
echo "</p>";

$session->Delete('csv');