<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\DeleteFileByClient;

use Fig\Http\Message\StatusCodeInterface;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Nonz250\Storage\App\Adapter\File\Command\DeleteImageByClient\DeleteImageByClientInput;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient\DeleteImageByClientInterface;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageByClient\DeleteImageException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpBadRequestException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpInternalErrorException;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use Psr\Log\LoggerInterface;

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

    public function __invoke(ServerRequest $request): JsonResponse
    {
        $requestBody = $request->getParsedBody();

        try {
            try {
                $clientId = new ClientId((string)$requestBody['client_id']);
            } catch (InvalidArgumentException $e) {
                $this->logger->error($e);
                throw new HttpBadRequestException($e->getMessage());
            }

            try {
                $input = new DeleteImageByClientInput($clientId);
                $this->deleteImageByClient->process($input);
            } catch (DeleteImageException $e) {
                $this->logger->error($e);
                throw new HttpInternalErrorException($e->getMessage());
            }
        } catch (HttpException $e) {
            $this->logger->error($e);
            return $e->getApiProblemResponse();
        }

        return new JsonResponse([], StatusCodeInterface::STATUS_NO_CONTENT);
    }
}
