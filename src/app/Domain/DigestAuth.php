<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain;

use Nonz250\Storage\App\Http\Auth\InvalidResponseException;

class DigestAuth implements DigestAuthInterface
{
    private const REALM = 'Secret Zone';
    private const SHA_256 = 'sha256';

    public function process(DigestAuthInputPort $inputPort): void
    {
        // TODO: DBからユーザー情報取得
        $userName = 'user';
        $password = 'pass';
        // TODO: シークレットキー取得
        $nonce = 'nonce';

        // @see https://tex2e.github.io/rfc-translater/html/rfc7616.html
        $A1 = hash(self::SHA_256, "$userName:" . self::REALM . ":$password");
        $A2 = hash(self::SHA_256, "{$inputPort->method()}:{$inputPort->uri()}");
        $validResponse = hash(self::SHA_256, "$A1:" . $nonce . ":{$inputPort->nc()}:{$inputPort->cnonce()}:{$inputPort->qop()}:$A2");

        if ($validResponse !== $inputPort->response()) {
            throw new InvalidResponseException();
        }
    }
}
