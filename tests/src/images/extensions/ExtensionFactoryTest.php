<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\Extensions\ExtensionFactory;
use PHPUnit\Framework\TestCase;

class ExtensionFactoryTest extends TestCase
{
   public function testInstantiates(): void
    {
        $factory = new ExtensionFactory();
        $this->assertInstanceOf(ExtensionFactory::class, $factory);
    }

    public function testBuildExtension(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extension_id' => 2,
            'extension' => 'jpg',
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertInstanceOf(Extension::class, $extension);
    }

    public function testNoBuildWhenMissingId(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extension' => 'jpg',
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertNull($extension);
    }

    public function testNoBuildWhenMissingExtension(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extension_id' => 2,
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertNull($extension);
    }

    public function testNoBuildWhenMissingPictureType(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extension_id' => 2,
            'extension' => 'jpg',
        ]);
        $this->assertNull($extension);
    }

    public function testNoBuildWhenIdNotNumber(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extension_id' => 'png',
            'extension' => 'jpg',
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertNull($extension);
    }

    public function testBuildWhenIdNumericString(): void
    {
        $factory = new ExtensionFactory();
        $extension = $factory->createFromQueryResult([
            'extension_id' => '1',
            'extension' => 'jpg',
            'picture_type' => 'image/jpeg',
        ]);
        $this->assertInstanceOf(Extension::class, $extension);
    }

    public function testBuildWhenExtraDataPresent(): void
    {
        $factory = new ExtensionFactory();
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
