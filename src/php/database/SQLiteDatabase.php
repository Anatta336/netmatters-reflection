<?php
namespace Netmatters\Database;

use Psr\Log\LoggerInterface;

class SQLiteDatabase implements DatabaseInterface
{
    protected \PDO $pdo;
    protected LoggerInterface $logger;

    function __construct(LoggerInterface $logger, string $pathToFile)
    {
        $this->logger = $logger;

        try {
            $dataSourceName = "sqlite:$pathToFile";
            $this->pdo = new \PDO($dataSourceName, '', '',
                [
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        } catch (\Exception $e) {
            $logger->alert("Unable to access SQLite database.",
                [$pathToFile, $e->getMessage(), $e]);
        }
    }

    public function fetchResults(string $sqlQuery, ...$values): array
    {
        try {
            $statement = $this->pdo->prepare($sqlQuery);
            
            for ($i = 0; $i < count($values); $i++) {
                $statement->bindValue($i + 1, $values[$i]);
            }
            $statement->execute();
        } catch (\Exception $e) {
            $this->logger->critical("Unable to fetch results.",
                [$sqlQuery, $values, $e->getMessage(), $e]);
        }
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function runQuery(string $sqlQuery, ...$values): bool
    {
        $result = false;
        try {
            $statement = $this->pdo->prepare($sqlQuery);

            for ($i = 0; $i < count($values); $i++) {
                $statement->bindValue($i + 1, $values[$i]);
            }
            $result = $statement->execute();
        } catch (\Exception $e) {
            $this->logger->critical("Unable to store values.",
                [$sqlQuery, $values, $e->getMessage(), $e]);
        }
        return $result;
    }
}
