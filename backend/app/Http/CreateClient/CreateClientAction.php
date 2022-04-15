<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\CreateClient;

use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Nonz250\Storage\App\Domain\Client\Command\CreateClient\CreateClientInput;
use Nonz250\Storage\App\Domain\Client\Command\CreateClient\CreateClientInterface;
use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Foundation\Exceptions\HttpBadRequestException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpInternalErrorException;
use PDOException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class CreateClientAction
{
    private LoggerInterface $logger;
    private CreateClientInterface $createClient;

    public function __construct(
        LoggerInterface $logger,
        CreateClientInterface $createClient
    ) {
        $this->logger = $logger;
        $this->createClient = $createClient;
    }

    public function __invoke(ServerRequest $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        try {
            try {
                $appName = new AppName($data['appName'] ?? '');
                $clientEmail = new ClientEmail($data['email'] ?? '');
            } catch (InvalidArgumentException $e) {
                $this->logger->error($e);
                throw new HttpBadRequestException($e->getMessage());
            }
        } catch (HttpException $e) {
            return $e->getApiProblemResponse();
        }

        try {
            try {
                $input = new CreateClientInput($appName, $clientEmail);
                $client = $this->createClient->process($input);
            } catch (PDOException $e) {
                $this->logger->error($e);
                throw new HttpInternalErrorException('Failed create client.');
            }
        } catch (HttpException $e) {
            $this->logger->error($e);
            return $e->getApiProblemResponse();
        }

        return new JsonResponse($client);
    }
}
