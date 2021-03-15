<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\Extensions\ExtensionCollection;
use PHPUnit\Framework\TestCase;

class ExtensionCollectionTest extends TestCase
{
    protected function createStubExtension($id, $extension, $pictureType): Extension
    {
        $stub = $this->createStub(Extension::class);
        $stub->method('getId')->willReturn($id);
        $stub->method('getExtension')->willReturn($extension);
        $stub->method('getPictureType')->willReturn($pictureType);
        return $stub;
    }

    public function testInstantiates(): void
    {
        $collection = new ExtensionCollection();
        $this->assertInstanceOf(ExtensionCollection::class, $collection);
    }

    public function testHas(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $collection = new ExtensionCollection();
        $collection->add($jpgStubExtension);
        $this->assertTrue($collection->has($jpgStubExtension));
    }
    public function testHasNot(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $collection = new ExtensionCollection();
        $this->assertFalse($collection->has($jpgStubExtension));
    }

    public function testHasById(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $collection = new ExtensionCollection();
        $collection->add($jpgStubExtension);
        $this->assertTrue($collection->hasId(1));
    }
    public function testHasNotById(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $collection = new ExtensionCollection();
        $collection->add($jpgStubExtension);
        $this->assertFalse($collection->hasId(0));
    }
    
    public function testGetById(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $collection = new ExtensionCollection();
        $collection->add($jpgStubExtension);
        $this->assertInstanceOf(Extension::class, $collection->get(1));
    }
    public function testGetByWrongIdGivesNull(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $collection = new ExtensionCollection();
        $collection->add($jpgStubExtension);
        $this->assertNull($collection->get(0));
    }

    public function testCountZero(): void
    {
        $collection = new ExtensionCollection();
        $this->assertSame(0, $collection->count());
    }
    public function testCountOne(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $collection = new ExtensionCollection();
        $collection->add($jpgStubExtension);
        $this->assertSame(1, $collection->count());
    }
    public function testCountTwo(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $pngStubExtension = $this->createStubExtension(4, 'png', 'image/png');
        $collection = new ExtensionCollection();
        $collection->add($jpgStubExtension);
        $collection->add($pngStubExtension);
        $this->assertSame(2, $collection->count());
    }
    
    public function testSameIdOverwrites(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $pngStubExtension = $this->createStubExtension(1, 'png', 'image/png');
        $collection = new ExtensionCollection();
        $collection->add($jpgStubExtension);
        $collection->add($pngStubExtension);
        $this->assertSame($pngStubExtension, $collection->get(1));
    }
    
    public function testOnlyAbleToAddExtensions(): void
    {
        $collection = new ExtensionCollection();
        $this->expectException(TypeError::class);
        $collection->add("Extension");
    }
    
    public function testOnlyAbleToAccessByIntegerId(): void
    {
        $jpgStubExtension = $this->createStubExtension(1, 'jpg', 'image/jpeg');
        $collection = new ExtensionCollection();
        $collection->add($jpgStubExtension);
        $this->expectException(TypeError::class);
        $collection->get("jpg");
    }
}
