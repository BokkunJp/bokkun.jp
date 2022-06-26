<?php

require dirname(__DIR__, 3). "/public/common/ajax-require.php";
require 'include.php';


IncludeFiles(AddPath(PUBLIC_COMMON_DIR, 'IMAGE'));

chdir(dirname(getcwd(), 2));

$imageResult = ReadImage(ajaxFlg:true);

echo json_encode($imageResult);
