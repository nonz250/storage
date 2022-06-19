<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\UploadFile;

use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Nonz250\Storage\App\Adapter\File\Command\UploadImage\UploadImageInput;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInterface;
use Nonz250\Storage\App\Domain\File\Exceptions\MimeTypeException;
use Nonz250\Storage\App\Domain\File\Exceptions\UploadFileException;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\FileString;
use Nonz250\Storage\App\Domain\File\ValueObject\MimeType;
use Nonz250\Storage\App\Foundation\Exceptions\Base64Exception;
use Nonz250\Storage\App\Foundation\Exceptions\HttpBadRequestException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpInternalErrorException;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;

final class UploadFileAction
{
    private LoggerInterface $logger;

    private UploadImageInterface $uploadImage;

    public function __construct(
        LoggerInterface $logger,
        UploadImageInterface $uploadImage
    ) {
        $this->logger = $logger;
        $this->uploadImage = $uploadImage;
    }

    public function __invoke(ServerRequest $request): ResponseInterface
    {
        $requestBody = $request->getParsedBody();

        try {
            try {
                $fileEncoded = $requestBody['file'] ?? '';
                $fileDecoded = base64_decode($fileEncoded, true);

                if ($fileDecoded === false) {
                    throw new Base64Exception('Failed to base64 decode.');
                }
                $fileString = new FileString($fileDecoded);

                $fileName = new FileName($requestBody['fileName'] ?? '');
                $clientId = new ClientId((string)$requestBody['client_id']);
                $mimeType = new MimeType($requestBody['mimetype'] ?? MimeType::MIME_TYPE_WEBP);
            } catch (InvalidArgumentException $e) {
                $this->logger->error($e);
                throw new HttpBadRequestException($e->getMessage());
            } catch (Base64Exception $e) {
                $this->logger->error($e);
                throw new HttpInternalErrorException($e->getMessage());
            }
        } catch (HttpException $e) {
            return $e->getApiProblemResponse();
        }

        try {
            try {
                $input = new UploadImageInput($clientId, $fileName, $fileString, $mimeType);
                $output = $this->uploadImage->process($input);
            } catch (MimeTypeException $e) {
                $this->logger->error($e);
                throw new HttpBadRequestException('Invalid mimetype.');
            } catch (UploadFileException $e) {
                $this->logger->error($e);
                throw new HttpInternalErrorException($e->getMessage());
            } catch (Throwable $e) {
                $this->logger->error($e);
                throw new HttpInternalErrorException();
            }
        } catch (HttpException $e) {
            $this->logger->error($e);
            return $e->getApiProblemResponse();
        }

        return new JsonResponse([
            'message' => 'Successfully created file.',
            'id' => (string)$output->identifier(),
            'originFileName' => $output->originFileName(),
            'fileName' => $output->fileName(),
            'originPath' => $output->originPath(),
            'thumbnailPath' => $output->thumbnailPath(),
        ]);
    }
}
