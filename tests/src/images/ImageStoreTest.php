<?php declare(strict_types=1);

use Netmatters\Database\DatabaseInterface;
use Netmatters\Images\ImageFactory;
use Netmatters\Images\ImageStore;
use Netmatters\Images\Image;
use PHPUnit\Framework\TestCase;

class ImageStoreTest extends TestCase
{
    protected function createStubDatabase(): DatabaseInterface
    {
        $stub = $this->createStub(DatabaseInterface::class);
        $stub->method('fetchResults')->willReturn([]);
        $stub->method('runQuery')->willReturn(true);
        return $stub;
    }

    protected function createStubImageFactory(): ImageFactory
    {
        $stub = $this->createStub(ImageFactory::class);
        $stub->method('createFromQueryResults')->willReturn(null);
        return $stub;
    }

    public function testInstantiates(): void
    {
        $database = $this->createStubDatabase();
        $factory = $this->createStubImageFactory();
        $store = new ImageStore($database, $factory);
        $this->assertInstanceOf(ImageStore::class, $store);
    }

    public function testSendsSqlToFetch(): void
    {
        $expectedSql = <<<"EOT"
            SELECT image.id AS id,
                image.image_url AS image_url,
                extension.id AS extension_id,
                extension.extension AS extension,
                extension.picture_type AS picture_type,
                image_has_extension.is_default AS is_default
            FROM image_has_extension
            JOIN image ON image_has_extension.image_id = image.id
            JOIN extension ON image_has_extension.extension_id = extension.id
            WHERE image.id = ?;
        EOT;

        $database = $this->createMock(DatabaseInterface::class);
        $database->expects($this->once())
            ->method('fetchResults')
            ->with(
                $this->equalTo($expectedSql),
                $this->anything(),
            );
        $factory = $this->createStubImageFactory();
        $store = new ImageStore($database, $factory);
        $this->assertInstanceOf(ImageStore::class, $store);

        // this method is expected to lead to fetchResults being called on the DatabaseInterface
        $store->getImageById(1);
    }

    public function testSendsIdToFetch(): void
    {
        $expectedSql = <<<"EOT"
            SELECT image.id AS id,
                image.image_url AS image_url,
                extension.id AS extension_id,
                extension.extension AS extension,
                extension.picture_type AS picture_type,
                image_has_extension.is_default AS is_default
            FROM image_has_extension
            JOIN image ON image_has_extension.image_id = image.id
            JOIN extension ON image_has_extension.extension_id = extension.id
            WHERE image.id = ?;
        EOT;

        $database = $this->createMock(DatabaseInterface::class);
        $database->expects($this->once())
            ->method('fetchResults')
            ->with(
                $this->anything(),
                $this->equalTo(1),
            );
        $factory = $this->createStubImageFactory();
        $store = new ImageStore($database, $factory);
        $this->assertInstanceOf(ImageStore::class, $store);

        // this method is expected to lead to fetchResults being called on the DatabaseInterface
        $store->getImageById(1);
    }
}
