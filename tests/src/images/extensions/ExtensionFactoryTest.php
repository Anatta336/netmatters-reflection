<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\Extensions\ExtensionFactory;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ExtensionFactoryTest extends TestCase
{
    protected LoggerInterface $logger;

    protected function createStubLogger(): LoggerInterface
    {
        $stub = $this->createStub(LoggerInterface::class);
        return $stub;
    }

    protected function setUp(): void
    {
        $this->logger = $this->createStubLogger();
    }

    public function testInstantiates(): void
    {
        $factory = new ExtensionFactory($this->logger);
        $this->assertInstanceOf(ExtensionFactory::class, $factory);
    }

    public function testBuildExtension(): void
    {
        $factory = new ExtensionFactory($this->logger);
        $extension = $factory->createFromQueryResult([
            'extension_id' => 2,
            'extension' => 'jpg',
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertInstanceOf(Extension::class, $extension);
    }

    public function testNoBuildWhenMissingId(): void
    {
        $factory = new ExtensionFactory($this->logger);
        $extension = $factory->createFromQueryResult([
            'extension' => 'jpg',
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertNull($extension);
    }

    public function testNoBuildWhenMissingExtension(): void
    {
        $factory = new ExtensionFactory($this->logger);
        $extension = $factory->createFromQueryResult([
            'extension_id' => 2,
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertNull($extension);
    }

    public function testNoBuildWhenMissingPictureType(): void
    {
        $factory = new ExtensionFactory($this->logger);
        $extension = $factory->createFromQueryResult([
            'extension_id' => 2,
            'extension' => 'jpg',
        ]);
        $this->assertNull($extension);
    }

    public function testNoBuildWhenIdNotNumber(): void
    {
        $factory = new ExtensionFactory($this->logger);
        $extension = $factory->createFromQueryResult([
            'extension_id' => 'png',
            'extension' => 'jpg',
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertNull($extension);
    }

    public function testBuildWhenIdNumericString(): void
    {
        $factory = new ExtensionFactory($this->logger);
        $extension = $factory->createFromQueryResult([
            'extension_id' => '1',
            'extension' => 'jpg',
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertInstanceOf(Extension::class, $extension);
    }

    public function testBuildWhenExtraDataPresent(): void
    {
        $factory = new ExtensionFactory($this->logger);
        $extension = $factory->createFromQueryResult([
            'extension_id' => '1',
            'is_default' => true,
            'extension' => 'jpg',
            'picture_type' => 'image/jpeg',
            'unexpectedItem' => $factory,
        ]);
        $this->assertInstanceOf(Extension::class, $extension);
    }
}
