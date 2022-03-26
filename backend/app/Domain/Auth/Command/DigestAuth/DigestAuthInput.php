<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Auth\Command\DigestAuth;

use Nonz250\Storage\App\Foundation\App;

class DigestAuthInput implements DigestAuthInputPort
{
    private array $data = [];
    private string $method;
    private string $nonce;

    public function __construct(string $value, string $method, string $nonce)
    {
        $this->parse($value);
        $this->method = $method;
        $this->nonce = $nonce;
    }

    public function userName(): string
    {
        return $this->data['username'] ?? '';
    }

    public function uri(): string
    {
        return $this->data['uri'] ?? '';
    }

    public function qop(): string
    {
        return $this->data['qop'] ?? '';
    }

    public function nc(): string
    {
        return $this->data['nc'] ?? '';
    }

    public function cnonce(): string
    {
        return $this->data['cnonce'] ?? '';
    }

    public function response(): string
    {
        return $this->data['response'] ?? '';
    }

    public function method(): string
    {
        return $this->method;
    }

    private function parse(string $value): void
    {
        preg_match_all(
            '@(cnonce|nc|qop|response|username|uri)=(?:([\'"])([^\2]+?)\2|([^\s,]+))@',
            $value,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $this->data[$match[1]] = $match[3] ?: $match[4];
        }
    }

    public function nonce(): string
    {
        return $this->nonce;
    }
}
