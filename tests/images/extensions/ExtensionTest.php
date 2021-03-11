<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{
    public function testStoresId(): void
    {
        $extension = new Extension(123, 'jpg', 'image/jpeg');
        $this->assertSame(123, $extension->getId());
    }
    public function testStoresExtension(): void
    {
        $extension = new Extension(123, 'jpg', 'image/jpeg');
        $this->assertSame('jpg', $extension->getExtension());
    }
    public function testStoresPictureType(): void
    {
        $extension = new Extension(123, 'jpg', 'image/jpeg');
        $this->assertSame('image/jpeg', $extension->getPictureType());
    }

    public function testRequiresId(): void
    {
        $this->expectException(TypeError::class);
        $extension = new Extension(null, 'jpg', 'image/jpeg');
    }
    public function testRequiresExtension(): void
    {
        $this->expectException(TypeError::class);
        $extension = new Extension(123, null, 'image/jpeg');
    }
    public function testRequiresPictureType(): void
    {
        $this->expectException(TypeError::class);
        $extension = new Extension(123, 'jpg', null);
    }

    public function testIdMustBeInt(): void
    {
        $this->expectException(TypeError::class);
        $extension = new Extension(12.32, 'jpg', 'image/jpeg');
    }
    public function testExtensionMustBeString(): void
    {
        $this->expectException(TypeError::class);
        $extension = new Extension(123, 456, 'image/jpeg');
    }
    public function testPictureTypeMustBeString(): void
    {
        $this->expectException(TypeError::class);
        $extension = new Extension(123, 'jpg', 456);
    }
}
