<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\DeleteFileByClient;

use Fig\Http\Message\StatusCodeInterface;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient\DeleteImageByClientInput;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient\DeleteImageByClientInterface;
use Nonz250\Storage\App\Foundation\Exceptions\HttpBadRequestException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpInternalErrorException;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class DeleteFileByClientAction
{
    private LoggerInterface $logger;

    private DeleteImageByClientInterface $deleteImageByClient;

    public function __construct(
        LoggerInterface $logger,
        DeleteImageByClientInterface $deleteImageByClient
    ) {
        $this->logger = $logger;
        $this->deleteImageByClient = $deleteImageByClient;
    }

    public function __invoke(ServerRequest $request): JsonResponse|ResponseInterface
    {
        $requestBody = $request->getParsedBody();

        try {
            try {
                $clientId = new ClientId((string)$requestBody['client_id']);
            } catch (InvalidArgumentException $e) {
                throw new HttpBadRequestException($e->getMessage(), $e);
            }

            try {
                $input = new DeleteImageByClientInput($clientId);
                $this->deleteImageByClient->process($input);
            } catch (Throwable $e) {
                throw new HttpInternalErrorException($e);
            }
        } catch (HttpException $e) {
            $this->logger->error($e);
            return $e->getApiProblemResponse();
        }

        return new JsonResponse([], StatusCodeInterface::STATUS_NO_CONTENT);
    }
}
