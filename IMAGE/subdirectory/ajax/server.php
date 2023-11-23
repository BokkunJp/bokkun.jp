<?php

require_once dirname(__DIR__, 3). "/public/common/ajax-require.php";
require_once'include.php';

$imagePath = new \Path(PUBLIC_COMMON_DIR);
$imagePath->add('image');
includeFiles($imagePath->get());

chdir(dirname(getcwd(), 2));

$imageResult = readImage(ajaxFlg:true);

echo json_encode($imageResult);
