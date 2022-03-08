<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain;

interface DigestAuthInterface
{
    public function process(DigestAuthInputPort $inputPort): void;
}
