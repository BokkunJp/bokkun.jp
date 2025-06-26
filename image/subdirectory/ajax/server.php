<?php

header("Content-Type: application/json; charset=UTF-8");
chdir(dirname(getcwd(), 2));
require_once dirname(__DIR__, 3). "/public/common/ajax-require.php";

$imagePath = new \Path(PUBLIC_COMMON_DIR);
$imagePath->add('image');
includeFiles($imagePath->get());


$imageResult = readImage(ajaxFlg:true);

echo json_encode($imageResult);
