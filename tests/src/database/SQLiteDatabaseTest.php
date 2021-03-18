<?php declare(strict_types=1);

use Netmatters\Database\DatabaseInterface;
use Netmatters\Database\SQLiteDatabase;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SQLiteDatabaseTest extends TestCase
{
    protected function createStubLogger(): LoggerInterface
    {
        $stub = $this->createStub(LoggerInterface::class);
        return $stub;
    }

    public function testImplementsDatabaseInterface(): void
    {
        $logger = $this->createStubLogger();
        $database = new SQLiteDatabase($logger, '');
        $this->assertInstanceOf(DatabaseInterface::class, $database);
    }
}
