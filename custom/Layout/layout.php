<?php

// セッションスタート
if (!isset($_SESSION)) {
    if (PHP_OS === 'WINNT') {
        $sessionDir = dirname(filter_input(INPUT_SERVER, 'DOCUMENT_ROOT')). "/var/session/";
        if (!is_dir($sessionDir)) {
            mkdir($sessionDir, 0755);
        }
        session_save_path($sessionDir);
    }
    session_start();
} else {
    session_reset();
}

?>
<!DOCTYPE html>
<?php

require_once __DIR__ . '/require.php';

$title = "カスタムテンプレート";
$img = "crown-vector.jpg";
?>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo $title; ?>
    </title>
    <base href="../" />
    <link rel="shortcut icon" href="/public/client/image/IMG_7592.PNG">
    <?php require_once dirname(__DIR__, 2) . '/public/common/Layout/init.php'; ?>
    <link rel="stylesheet" type="text/css"
        href="/public/client/css/<?php echo $homepageTitle; ?>/design.css">
</head>

<body>
    <link rel="stylesheet" type="text/css"
        href="<?php echo $public; ?>client/css/common/<?php echo $agentCode; ?>.css">
    <div class="container">
        <?php require_once('header.php'); ?>
        <main class="contents">
            <?php require_once(getcwd() . '/design.php'); ?>
        </main>
        <?php require_once('footer.php'); ?>
    </div>
</body>

</html>