<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\DeleteFileById;

use Fig\Http\Message\StatusCodeInterface;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageById\DeleteImageByIdInput;
use Nonz250\Storage\App\Domain\File\Command\DeleteImageById\DeleteImageByIdInterface;
use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Foundation\Exceptions\HttpBadRequestException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpInternalErrorException;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class DeleteFileByIdAction
{
    private LoggerInterface $logger;

    private DeleteImageByIdInterface $deleteImageById;

    public function __construct(
        LoggerInterface $logger,
        DeleteImageByIdInterface $deleteImageById
    ) {
        $this->logger = $logger;
        $this->deleteImageById = $deleteImageById;
    }

    public function __invoke(ServerRequest $request, array $args): JsonResponse|ResponseInterface
    {
        $requestBody = $request->getParsedBody();

        try {
            try {
                $clientId = new ClientId((string)$requestBody['client_id']);
                $fileIdentifier = new FileIdentifier($args['fileIdentifier'] ?? '');
            } catch (InvalidArgumentException $e) {
                throw new HttpBadRequestException($e->getMessage(), $e);
            }

            try {
                $input = new DeleteImageByIdInput($clientId, $fileIdentifier);
                $this->deleteImageById->process($input);
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
