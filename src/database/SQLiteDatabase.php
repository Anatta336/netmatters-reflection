<?php
namespace Netmatters;

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
            echo "Unable to access SQLite database at $pathToFile\n"
                . $e->getMessage();
        }
    }

    public function fetchResults(string $sqlQuery, ...$values): array
    {
        $statement = $this->pdo->prepare($sqlQuery);

        for ($i = 0; $i < count($values); $i++) {
            $statement->bindValue($i + 1, $values[$i]);
        }
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }
}
