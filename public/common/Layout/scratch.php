<?php

// セッションスタート
if (!isset($_SESSION)) {
    session_start();
} else {
    session_reset();
}

?>
<!DOCTYPE html>
<?php

require_once __DIR__. '/require.php';
