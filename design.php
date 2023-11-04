<div class="thanks">ご閲覧ありがとうございます！</div>
まだ何もありせんが、少しずつコンテンツを増やす予定です！！<br />
<small>
    スマートフォン・タブレットからのアクセス非推奨。<br />
    また、ガラケーは未対応です。
</small>
<div>
    <h1>サイト一覧</h1>
    <ul>
        <li>PHP</li>
    </ul>
    <ol>
        <?php
    $notList = ['.', '..', '.htaccess', 'client', 'common', 'template', 'template_base', 'custom', 'custom_base', 'public', 'cake', 'private', 'API', 'php.yml'];
    $dirList = scandir(__DIR__ . '/');
    $notList = addList($notList, $dirList, '.', 1);
    $notList = addList($notList, $dirList, '_', 1);

    foreach ($dirList as $index => $_dir) {
        if (!searchData($_dir, $notList) && !preg_match("/\.php$|\.html$/", $_dir)) {
            echo "<li><a href=\"./$_dir/\" target=\"_new\">$_dir</a></li>";
        }
    }
    ?>
    </ol>
    <ul>
        <li>CakePHP4</li>
    </ul>
    <ol>
        <?php
            $notList = [
            '.', '..', 'cell', 'element', 'email', 'Error', 'layout',
            'Pages'
            ];
            $dirList = scandir(dirname(__DIR__) . '/cake.bokkun.jp/templates');
            $notList = addList($notList, $dirList, '_', 1);
            foreach ($dirList as $index => $_dir) {
                if (!searchData($_dir, $notList)) {
                    echo "<li><a href=\"https://cake.{$domain}/{$_dir}/\" target=\"_blank\">$_dir</a></li>";
                }
            }
    ?>
    </ol>
    <?php require_once "history.php" ?>
</div>