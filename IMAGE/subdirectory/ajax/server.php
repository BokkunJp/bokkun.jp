<?php

require_once dirname(__DIR__, 3). "/public/common/ajax-require.php";
require_once'include.php';

$imagePath = new \Path(PUBLIC_COMMON_DIR);
$imagePath->Add('IMAGE');
IncludeFiles($imagePath->Get());

chdir(dirname(getcwd(), 2));

$imageResult = ReadImage(ajaxFlg:true);

echo json_encode($imageResult);
