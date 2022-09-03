<?php
declare(strict_types=1);

namespace Nonz250\Storage\App\Foundation\Model;

use PDO;
use PDOException;
use PDOStatement;

final class Model
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function beginTransaction(): void
    {
        if (!$this->pdo->beginTransaction()) {
            throw new PDOException('Failed begin transaction.');
        }
    }

    public function commit(): void
    {
        if (!$this->pdo->commit()) {
            throw new PDOException('Failed commit.');
        }
    }

    public function rollBack(): void
    {
        if (!$this->pdo->rollBack()) {
            throw new PDOException('Failed roll back.');
        }
    }

    public function execute(string $sql, ?BindValues $bindValues = null): PDOStatement
    {
        $statement = $this->pdo->prepare($sql);

        if ($statement === false) {
            throw new PDOException('Failed prepared query.');
        }

        if ($bindValues && !$bindValues->isEmpty()) {
            foreach ($bindValues as $bindKey => $bindValue) {
                $statement->bindValue($bindKey, $bindValue);
            }
        }

        if (!$statement->execute()) {
            throw new PDOException('Failed execute statement.');
        }

        return $statement;
    }

    public function select(string $sql, ?BindValues $bindValues = null): array
    {
        try {
            $statement = $this->execute($sql, $bindValues);
        } catch (PDOException $e) {
            throw new PDOException('Failed execute select.');
        }

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new PDOException('Failed fetch all.');
        }

        return $result;
    }

    public function insert(string $sql, BindValues $bindValues): void
    {
        try {
            $this->execute($sql, $bindValues);
        } catch (PDOException $e) {
            throw new PDOException('Failed execute select.');
        }
    }

    public function delete(string $sql, BindValues $bindValues): void
    {
        try {
            $this->execute($sql, $bindValues);
        } catch (PDOException $e) {
            throw new PDOException('Failed execute delete.');
        }
    }
}
