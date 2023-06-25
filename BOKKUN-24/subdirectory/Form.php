<?php
$basePath = new \Path(__DIR__);
$basePath->Add("Base");
IncludeFiles($basePath->Get(), 'php', false, ['BaseValid']);
class Valid extends BaseVaild
{
}

$valid = new Valid();
