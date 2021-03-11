<?php declare(strict_types=1);

use Netmatters\Database\DatabaseInterface;
use Netmatters\Database\SQLiteDatabase;
use PHPUnit\Framework\TestCase;

class SQLiteDatabaseTest extends TestCase
{
    private function createDatabase(): SQLiteDatabase
    {
        // shouldn't be using external "real" data like this as part of tests
        // return new SQLiteDatabase('db/netmatters.db');

        // instead this creates a temporary SQLite database, but can still do these tests
        return new SQLiteDatabase('');
    }

    public function testImplementsDatabaseInterface(): void
    {
        $database = $this->createDatabase();
        $this->assertInstanceOf(DatabaseInterface::class, $database);
    }
}
