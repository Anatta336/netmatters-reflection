<?php
namespace Netmatters\Database;

class SQLiteDatabase implements DatabaseInterface
{
    protected \PDO $pdo;

    function __construct(string $pathToFile)
    {
        $dataSourceName = "sqlite:$pathToFile";

        try {
            $this->pdo = new \PDO($dataSourceName, '', '',
                [
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                ]
            );
        } catch (\Exception $e) {
            // TODO: log this instead of a random echo
            echo "Unable to access SQLite database at $pathToFile\n"
                . $e->getMessage();
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
            // TODO: log this
            echo "Unable to fetch results.\n"
                . $e->getMessage();
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
            // TODO: log this
            echo "Unable to store values.\n"
                . $e->getMessage();
        }
        return $result;
    }
}
