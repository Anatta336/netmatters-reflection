<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\Image;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{
    protected $jpg;
    protected $png;
    protected $webp;

    protected function createStubExtension(
        int $id, string $extension, string $pictureType): Extension
    {
        $stub = $this->createStub(Extension::class);
        $stub->method('getId')->willReturn($id);
        $stub->method('getExtension')->willReturn($extension);
        $stub->method('getPictureType')->willReturn($pictureType);
        return $stub;
    }

    protected function setUp(): void
    {
        $this->jpg = $this->createStubExtension(1, '.jpg', 'image/jpeg');
        $this->png = $this->createStubExtension(1, '.png', 'image/png');
        $this->webp = $this->createStubExtension(1, '.webp', 'image/webp');
    }

    public function testInstantiates(): void
    {
        $image = new Image(123, 'images/picture', [$this->jpg], $this->jpg);
        $this->assertInstanceOf(Image::class, $image);
    }
    
    public function testStoresId(): void
    {
        $image = new Image(123, 'images/picture', [$this->jpg], $this->jpg);
        $this->assertSame(123, $image->getId());
    }
    public function testStoresImageUrl(): void
    {
        $image = new Image(123, 'images/picture', [$this->jpg], $this->jpg);
        $this->assertSame('images/picture', $image->getImageUrl());
    }
    public function testStoresDefaultExtension(): void
    {
        $image = new Image(123, 'images/picture', [$this->jpg], $this->jpg);
        $this->assertSame($this->jpg, $image->getDefaultExtension());
    }
    public function testGivesExtensionsAsArray(): void
    {
        $image = new Image(123, 'images/picture', [$this->jpg, $this->png], $this->jpg);
        $this->assertIsArray($image->getExtensions());
    }
    public function testStoresMultipleExtensions(): void
    {
        $image = new Image(123, 'images/picture', [$this->jpg, $this->png], $this->jpg);
        $this->assertSame([$this->jpg, $this->png], $image->getExtensions());
    }
    
    public function testImageUrlEscapesHtml(): void
    {
        $image = new Image(123, '<script>alert(1);</script>', [$this->jpg], $this->jpg);
        $this->assertSame('&lt;script&gt;alert(1);&lt;/script&gt;', $image->getImageUrl());
    }
    public function testImageUrlEscapesSingleQuotes(): void
    {
        $image = new Image(123, "' onload='alert(1);'", [$this->jpg], $this->jpg);
        $this->assertSame('&#039; onload=&#039;alert(1);&#039;', $image->getImageUrl());
    }
    public function testImageUrlEscapesDoubleQuotes(): void
    {
        $image = new Image(123, "\" onload=\"alert(1);\"", [$this->jpg], $this->jpg);
        $this->assertSame('&quot; onload=&quot;alert(1);&quot;', $image->getImageUrl());
    }
    
}
