<?php declare(strict_types=1);

use Netmatters\DatabaseInterface;
use Netmatters\SQLiteDatabase;
use PHPUnit\Framework\TestCase;

class SQLiteDatabaseTest extends TestCase
{
    private string $databasePath = 'db/netmatters.db';

    private function createDatabase(): SQLiteDatabase
    {
        return new SQLiteDatabase($this->databasePath);
    }

    public function testImplementsDatabaseInterface(): void
    {
        $database = $this->createDatabase();
        $this->assertInstanceOf(DatabaseInterface::class, $database);
    }

    public function testProvidesPDO(): void
    {
        $database = $this->createDatabase();
        $this->assertInstanceOf(\PDO::class, $database->getPDO());
    }
}
