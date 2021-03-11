<?php declare(strict_types=1);

use Netmatters\Images\Image;
use Netmatters\Images\ImageCollection;
use PHPUnit\Framework\TestCase;

class ImageCollectionTest extends TestCase
{
    protected function createPartialStubImage(int $id): Image
    {
        // ImageCollection only needs the getId method, so only provide that
        $stub = $this->createStub(Image::class);
        $stub->method('getId')->willReturn($id);
        return $stub;
    }

    public function testInstantiates(): void
    {
        $collection = new ImageCollection();
        $this->assertInstanceOf(ImageCollection::class, $collection);
    }
    
    public function testStartsAtZeroCount(): void
    {
        $collection = new ImageCollection();
        $this->assertSame(0, $collection->count());
    }
    
    public function testCountReachesOne(): void
    {
        $collection = new ImageCollection();
        $collection->add($this->createPartialStubImage(1));
        $this->assertSame(1, $collection->count());
    }
    public function testCountReachesTwo(): void
    {
        $collection = new ImageCollection();
        $collection->add($this->createPartialStubImage(1));
        $collection->add($this->createPartialStubImage(5));
        $this->assertSame(2, $collection->count());
    }
    public function testCountReachesThree(): void
    {
        $collection = new ImageCollection();
        $collection->add($this->createPartialStubImage(1));
        $collection->add($this->createPartialStubImage(5));
        $collection->add($this->createPartialStubImage(0));
        $this->assertSame(3, $collection->count());
    }

    public function testCanNotAddString(): void
    {
        $collection = new ImageCollection();
        $this->expectException(TypeError::class);
        $collection->add('Image');
    }
    public function testCanNotAddOtherType(): void
    {
        $someObject = new class
        {
            public function getId()
            {
                return 1;
            }
        };

        $collection = new ImageCollection();
        $this->expectException(TypeError::class);
        $collection->add($someObject);
    }
    
    public function testCanRetrieveById(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $this->assertSame($imageOne, $collection->get(123));
    }
    public function testCanRetrieveByIdAfterMultipleAdded(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $imageTwo = $this->createPartialStubImage(456);
        $imageThree = $this->createPartialStubImage(0);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $collection->add($imageTwo);
        $collection->add($imageThree);
        $this->assertSame($imageTwo, $collection->get(456));
    }

    public function testSameIdOverwrites(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $imageTwo = $this->createPartialStubImage(123);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $collection->add($imageTwo);
        $this->assertSame($imageTwo, $collection->get(123));
        $this->assertNotSame($imageOne, $collection->get(123));
    }

    public function testCanCheckForPresenceByIdWhenPositive(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $this->assertTrue($collection->hasId(123));
    }
    public function testCanCheckForPresenceByIdWhenNegative(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $this->assertFalse($collection->hasId(1));
    }

    public function testCanCheckForPresenceByImageWhenPositive(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $this->assertTrue($collection->has($imageOne));
    }
    public function testCanCheckForPresenceByImageWhenNegative(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $imageTwo = $this->createPartialStubImage(456);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $this->assertFalse($collection->has($imageTwo));
    }
    public function testCanCheckForPresenceByImageWithSameIdWhenNegative(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $imageTwo = $this->createPartialStubImage(123);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $this->assertFalse($collection->has($imageTwo));
    }
    public function testCanCheckForPresenceByImageWhenNegativeAfterOverwriting(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $imageTwo = $this->createPartialStubImage(123);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $collection->add($imageTwo);
        $this->assertFalse($collection->has($imageOne));
        $this->assertTrue($collection->has($imageTwo));
    }

    public function testNullWhenGetWithInvalidId(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $this->assertNull($collection->get(1));
    }

    public function testErrorOnWrongTypeWhenCheckingPresence(): void
    {
        $imageOne = $this->createPartialStubImage(123);
        $collection = new ImageCollection();
        $collection->add($imageOne);
        $this->expectException(TypeError::class);
        $collection->has('imageOne');
    }
}
