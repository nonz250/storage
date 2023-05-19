<?php
declare(strict_types=1);

namespace Tests\Foundation;

use League\Container\DefinitionContainerInterface;
use Nonz250\Storage\App\Domain\Auth\ClientRepositoryInterface;
use Nonz250\Storage\App\Domain\Client\Client;
use Nonz250\Storage\App\Domain\Client\ValueObject\AppName;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientEmail;
use Nonz250\Storage\App\Domain\Client\ValueObject\ClientSecret;
use Nonz250\Storage\App\Domain\File\FileFactoryInterface;
use Nonz250\Storage\App\Domain\File\FileRepositoryInterface;
use Nonz250\Storage\App\Domain\File\ValueObject\FileIdentifier;
use Nonz250\Storage\App\Domain\File\ValueObject\FileName;
use Nonz250\Storage\App\Domain\File\ValueObject\FileString;
use Nonz250\Storage\App\Foundation\Exceptions\DataNotFoundException;
use Nonz250\Storage\App\Foundation\Model\BindValues;
use Nonz250\Storage\App\Foundation\Model\Model;
use Nonz250\Storage\App\Shared\ValueObject\ClientId;
use PHPUnit\Framework\TestCase;
use Tests\RepositoryTestTrait;
use Tests\StringTestHelper;

final class RepositoryTest extends TestCase
{
    use RepositoryTestTrait;

    private ?DefinitionContainerInterface $container = null;

    private Model $model;

    /**
     * 2つ以上の異なる Repository から insert 処理をして Model から rollback したら成功すること
     * つまり PDO が singleton になっていて共通のインスタンスを利用していること.
     *
     * @return void
     */
    public function testRollback(): void
    {
        $this->model = $this->make(Model::class);

        $this->model->beginTransaction();

        [$clientId, $fileIdentifier] = $this->commonCase();

        $this->model->rollBack();

        // データが無いこと
        /** @var ClientRepositoryInterface $clientRepository */
        $clientRepository = $this->make(ClientRepositoryInterface::class);
        $this->expectException(DataNotFoundException::class);
        $clientRepository->findById($clientId);

        // 件数が0であること
        $this->assertCount(0, $this->findById($fileIdentifier));
    }

    /**
     * 2つ以上の異なる Repository から insert 処理をして Model から commit したら成功すること
     * つまり PDO が singleton になっていて共通のインスタンスを利用していること.
     *
     * @return void
     */
    public function testCommit(): void
    {
        $this->model = $this->make(Model::class);

        $this->model->beginTransaction();

        [$clientId, $fileIdentifier] = $this->commonCase();

        $this->model->commit();

        // データがあること
        /** @var ClientRepositoryInterface $clientRepository */
        $clientRepository = $this->make(ClientRepositoryInterface::class);
        $result = $clientRepository->findById($clientId);
        $this->assertSame((string)$clientId, (string)$result->clientId());

        // 件数が0であること
        $this->assertCount(1, $this->findById($fileIdentifier));
    }

    /**
     * @return array<ClientId|FileIdentifier>
     */
    private function commonCase(): array
    {
        /** @var ClientRepositoryInterface $clientRepository */
        $clientRepository = $this->make(ClientRepositoryInterface::class);

        /** @var FileRepositoryInterface $fileRepository */
        $fileRepository = $this->make(FileRepositoryInterface::class);

        $clientId = new ClientId(StringTestHelper::randomByHex(ClientId::LENGTH));
        $clientRepository->create(new Client(
            $clientId,
            new ClientSecret(StringTestHelper::randomByHex(ClientSecret::LENGTH)),
            new AppName(StringTestHelper::random(AppName::MAX_LENGTH)),
            new ClientEmail(StringTestHelper::randomEmail()),
        ));

        $result = $clientRepository->findById($clientId);
        $this->assertSame((string)$clientId, (string)$result->clientId());

        /** @var FileFactoryInterface $fileFactory */
        $fileFactory = $this->make(FileFactoryInterface::class);
        $fileName = new FileName(StringTestHelper::random(FileName::MAX_LENGTH));
        $file = $fileFactory->newImageFile($clientId, $fileName, new FileString(StringTestHelper::randomImageString()));

        $fileRepository->create($file);

        $this->assertCount(1, $this->findById($file->identifier()));

        return [$clientId, $file->identifier()];
    }

    private function findById(FileIdentifier $fileIdentifier): array
    {
        $sql = 'SELECT * FROM `files` WHERE `id` = :id';
        $bindValues = new BindValues();
        $bindValues->bindValue(':id', (string)$fileIdentifier);
        return $this->model->select($sql, $bindValues);
    }
}
