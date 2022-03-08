<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\DigestAuth;

interface DigestAuthInputPort
{
    public function userName(): string;

    public function uri(): string;

    public function qop(): string;

    public function nc(): string;

    public function cnonce(): string;

    public function response(): string;

    public function method(): string;
}
