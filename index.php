<?php
session_start();
require_once __DIR__ . '/common/Component/Function.php';
?>
<!DOCTYPE html>

<head>
    <meta name="robots" content="noindex,nofollow">
    <meta charset='utf-8' />
    <title>トップページ</title>
    <link rel="shortcut icon" href="public/client/image/5959715.png">
    <script src="//code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="public/client/js/common/time/realtime.js"></script>
    <script src="public/client/js/common/time/time.js"></script>
</head>


<body>
    <div class='container'>
        <?php require_once('./common/layout.php'); ?>
    </div>
</body>

</html>