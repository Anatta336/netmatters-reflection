<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\Extensions\ExtensionFactory;
use PHPUnit\Framework\TestCase;

class ExtensionFactoryTest extends TestCase
{
   public function testInstantiate(): void
    {
        $factory = new ExtensionFactory();
        $this->assertInstanceOf(ExtensionFactory::class, $factory);
    }

    public function testBuildExtension(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extensionId' => 2,
            'extension' => 'jpg',
            'pictureType' => 'image/jpeg',
        ]);
        $this->assertInstanceOf(Extension::class, $extension);
    }

    public function testNoBuildWhenMissingId(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extension' => 'jpg',
            'pictureType' => 'image/jpeg',
        ]);
        $this->assertNull($extension);
    }

    public function testNoBuildWhenMissingExtension(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extensionId' => 2,
            'pictureType' => 'image/jpeg',
        ]);
        $this->assertNull($extension);
    }

    public function testNoBuildWhenMissingPictureType(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extensionId' => 2,
            'extension' => 'jpg',
        ]);
        $this->assertNull($extension);
    }

    public function testNoBuildWhenIdNotNumber(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extensionId' => 'png',
            'extension' => 'jpg',
            'pictureType' => 'image/jpeg',
        ]);
        $this->assertNull($extension);
    }

    public function testBuildWhenIdNumericString(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extensionId' => '1',
            'extension' => 'jpg',
            'pictureType' => 'image/jpeg',
        ]);
        $this->assertInstanceOf(Extension::class, $extension);
    }

    public function testBuildWhenExtraDataPresent(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extensionId' => '1',
            'isDefault' => true,
            'extension' => 'jpg',
            'pictureType' => 'image/jpeg',
            'unexpectedItem' => $factory,
        ]);
        $this->assertInstanceOf(Extension::class, $extension);
    }
}
