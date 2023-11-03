<?php
$basePath = new \Path(__DIR__);
$basePath->add("Base");
includeFiles($basePath->get(), 'php', false, ['BaseValid']);
class Valid extends BaseVaild
{
}

$valid = new Valid();
