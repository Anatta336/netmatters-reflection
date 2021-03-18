<?php declare(strict_types=1);

use Netmatters\Database\DatabaseInterface;
use Netmatters\Database\SQLiteDatabase;
use PHPUnit\Framework\TestCase;

class SQLiteDatabaseTest extends TestCase
{
    public function testImplementsDatabaseInterface(): void
    {
        $database = new SQLiteDatabase('');
        $this->assertInstanceOf(DatabaseInterface::class, $database);
    }
}
