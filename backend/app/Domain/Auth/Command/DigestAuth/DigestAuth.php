<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Domain\Auth\Command\DigestAuth;

use Nonz250\Storage\App\Domain\Auth\ClientRepositoryInterface;
use Nonz250\Storage\App\Http\Auth\InvalidResponseException;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;

final class DigestAuth implements DigestAuthInterface
{
    private const REALM = 'Secret Zone';

    private const SHA_256 = 'sha256';

    private ClientRepositoryInterface $clientRepository;

    public function __construct(ClientRepositoryInterface $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function process(DigestAuthInputPort $inputPort): void
    {
        $client = $this->clientRepository->findById(new ClientId($inputPort->userName()));
        $userName = (string)$client->clientId();
        $password = (string)$client->clientSecret();
        $nonce = $inputPort->nonce();

        /** @see https://tex2e.github.io/rfc-translater/html/rfc7616.html */
        $A1 = hash(self::SHA_256, "$userName:" . self::REALM . ":$password");
        $A2 = hash(self::SHA_256, "{$inputPort->method()}:{$inputPort->uri()}");
        $validResponse = hash(self::SHA_256, "$A1:" . $nonce . ":{$inputPort->nc()}:{$inputPort->cnonce()}:{$inputPort->qop()}:$A2");

        if ($validResponse !== $inputPort->response()) {
            throw new InvalidResponseException();
        }
    }
}
