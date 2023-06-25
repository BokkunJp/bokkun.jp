<?php

$traitPath = new \Path(COMMON_DIR);
$traitPath->Add('Trait');
IncludeFiles($traitPath->Get());
trait PublicTrait
{
    use CommonTrait;
}
