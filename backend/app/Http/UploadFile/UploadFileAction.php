<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Http\UploadFile;

use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use Nonz250\Storage\App\Adapter\File\Command\UploadImage\UploadImageInput;
use Nonz250\Storage\App\Domain\File\Command\UploadImage\UploadImageInterface;
use Nonz250\Storage\App\Domain\File\Exceptions\MimeTypeException;
use Nonz250\Storage\App\Domain\File\ValueObject\Image;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Foundation\Exceptions\Base64Exception;
use Nonz250\Storage\App\Foundation\Exceptions\HttpBadRequestException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpException;
use Nonz250\Storage\App\Foundation\Exceptions\HttpInternalErrorException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class UploadFileAction
{
    private UploadImageInterface $uploadImage;

    public function __construct(UploadImageInterface $uploadImage)
    {
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
                $file = new Image($fileDecoded);

                $fileName = new FileName($requestBody['fileName'] ?? '');
            } catch (InvalidArgumentException $e) {
                // TODO: ログ記録
                throw new HttpBadRequestException($e->getMessage());
            } catch (Base64Exception $e) {
                // TODO: ログ記録
                throw new HttpInternalErrorException($e->getMessage());
            }
        } catch (HttpException $e) {
            return $e->getApiProblemResponse();
        }

        try {
            try {
                $input = new UploadImageInput($fileName, $file);
                $file = $this->uploadImage->process($input);
            } catch (MimeTypeException $e) {
                throw new HttpBadRequestException('Invalid mimetype.');
            } catch (Throwable $e) {
                throw new HttpInternalErrorException();
            }
        } catch (HttpException $e) {
            return $e->getApiProblemResponse();
        }

        return new JsonResponse([
            'message' => 'Successfully created file.',
            'originFileName' => $file->fullFileName(),
            'fileName' => $file->fullUniqueFileName(),
            'url' => sprintf('%s/%s', $_SERVER['HTTP_HOST'], $file->fullUniqueFileName()),
        ]);
    }
}