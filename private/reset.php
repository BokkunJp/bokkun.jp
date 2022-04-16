<?php
require __DIR__. "/common/require.php";
require_once "common.php";

$title = 'ログアウト';
// アクセス警告メール
AlertAdmin('access', $title);
$reset = explode(basename(__FILE__), '.')[0];
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset='utf-8' />
    <title>管理側</title>
    <link rel="stylesheet" type="text/css" href="./client/css/common.css">
    <link rel="stylesheet" type="text/css"
        href="./client/css/<?=$reset ?>/design.css">
</head>
<?php require_once "./common/header.php" ?>

<body>
    <div class="adminContents">
        <?= Logout() ?>
    </div>
    <?php require_once "./common/footer.php"; ?>
</body>

</html>