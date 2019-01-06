<?php
// 共通部分
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('API_DIR', DOCUMENT_ROOT . '/API');
define('COMMON_DIR', __DIR__);
define('PUBLIC_DIR', DOCUMENT_ROOT . '/public');
define('CLIENT_DIR', PUBLIC_DIR . '/client');
define('CSS_DIR', CLIENT_DIR . '/css');
define('JS_DIR', CLIENT_DIR . '/js');
define('IMAGE_DIR', CLIENT_DIR . '/image');
define('CSV_DIR', CLIENT_DIR . '/csv');
define('COMPONENT_DIR', COMMON_DIR . '/Component');
define('LAYOUT_DIR', COMMON_DIR . '/Layout');
define('DEBUG_CODE', __FILE__ . ':' . __LINE__);
define('NOW_PAGE', basename(getcwd()));


// FILEページの文言
define('PAGING', 10);
define('COUNT_START', 2);
define('PREVIOUS', 'ファイルページへ戻る');
define('ERRMessage', 'エラーが発生しました。');
