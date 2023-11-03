<?php

$traitPath = new \Path(COMMON_DIR);
$traitPath->add('Trait');
includeFiles($traitPath->get());
trait PublicTrait
{
    use CommonTrait;
}
