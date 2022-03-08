<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\DigestAuth;

interface DigestAuthInterface
{
    public function process(DigestAuthInputPort $inputPort): void;
}
