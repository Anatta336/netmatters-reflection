<?php declare(strict_types=1);

use Netmatters\Images\Extension;
use Netmatters\Images\ImageView;
use Netmatters\Images\Image;
use PHPUnit\Framework\TestCase;

class ImageViewTest extends TestCase
{
    protected $jpgExtensionStub;
    protected $pngExtensionStub;
    protected $webpExtensionStub;

    protected function setUp(): void
    {
        $this->jpgExtensionStub = $this->createStub(Extension::class);
        $this->jpgExtensionStub->method('getExtension')->willReturn('jpg');
        $this->jpgExtensionStub->method('getPictureType')->willReturn('image/jpeg');

        $this->pngExtensionStub = $this->createStub(Extension::class);
        $this->pngExtensionStub->method('getExtension')->willReturn('png');
        $this->pngExtensionStub->method('getPictureType')->willReturn('image/png');

        $this->webpExtensionStub = $this->createStub(Extension::class);
        $this->webpExtensionStub->method('getExtension')->willReturn('webp');
        $this->webpExtensionStub->method('getPictureType')->willReturn('image/webp');
    }

    public function testSingleExtension(): void
    {
        $imageStub = $this->createStub(Image::class);
        $imageStub->method('getId')->willReturn(123);
        $imageStub->method('getImageUrl')->willReturn('img/something/picture');
        $imageStub->method('getExtensions')->willReturn([$this->jpgExtensionStub]);
        $imageStub->method('getDefaultExtension')->willReturn($this->jpgExtensionStub);

        $imageView = new ImageView();
        $result = $imageView->pictureHtml($imageStub, 'http://example.com/', 'alternate text');
        $expected = '<picture>' . "\n"
            . '<source srcset="http://example.com/img/something/picture.jpg" type="image/jpeg">' . "\n"
            . '<img src="http://example.com/img/something/picture.jpg" alt="alternate text">' . "\n"
            . '</picture>' . "\n";
        $this->assertSame($expected, $result);
    }

    public function testMultipleExtensions(): void
    {
        $imageStub = $this->createStub(Image::class);
        $imageStub->method('getId')->willReturn(123);
        $imageStub->method('getImageUrl')->willReturn('img/something/picture');
        $imageStub->method('getExtensions')->willReturn([
            $this->jpgExtensionStub,
            $this->pngExtensionStub,
            $this->webpExtensionStub
        ]);
        $imageStub->method('getDefaultExtension')->willReturn($this->jpgExtensionStub);

        $imageView = new ImageView();
        $result = $imageView->pictureHtml($imageStub, 'http://example.com/', 'alternate text');
        $expected = '<picture>' . "\n"
            . '<source srcset="http://example.com/img/something/picture.jpg" type="image/jpeg">' . "\n"
            . '<source srcset="http://example.com/img/something/picture.png" type="image/png">' . "\n"
            . '<source srcset="http://example.com/img/something/picture.webp" type="image/webp">' . "\n"
            . '<img src="http://example.com/img/something/picture.jpg" alt="alternate text">' . "\n"
            . '</picture>' . "\n";
        $this->assertSame($expected, $result);
    }

    public function testZeroExtensions(): void
    {
        $imageStub = $this->createStub(Image::class);
        $imageStub->method('getId')->willReturn(123);
        $imageStub->method('getImageUrl')->willReturn('img/something/picture');
        $imageStub->method('getExtensions')->willReturn([]);
        $imageStub->method('getDefaultExtension')->willReturn($this->jpgExtensionStub);

        $imageView = new ImageView();
        $result = $imageView->pictureHtml($imageStub, 'http://example.com/', 'alternate text');
        $expected = '<picture>' . "\n"
            . '<img src="http://example.com/img/something/picture.jpg" alt="alternate text">' . "\n"
            . '</picture>' . "\n";
        $this->assertSame($expected, $result);
    }

    public function testMultipleExtensionsAndDifferentDefault(): void
    {
        $imageStub = $this->createStub(Image::class);
        $imageStub->method('getId')->willReturn(123);
        $imageStub->method('getImageUrl')->willReturn('img/something/picture');
        $imageStub->method('getExtensions')->willReturn([
            $this->jpgExtensionStub,
            $this->pngExtensionStub,
            $this->webpExtensionStub
        ]);
        $imageStub->method('getDefaultExtension')->willReturn($this->pngExtensionStub);

        $imageView = new ImageView();
        $result = $imageView->pictureHtml($imageStub, 'http://example.com/', 'alternate text');
        $expected = '<picture>' . "\n"
            . '<source srcset="http://example.com/img/something/picture.jpg" type="image/jpeg">' . "\n"
            . '<source srcset="http://example.com/img/something/picture.png" type="image/png">' . "\n"
            . '<source srcset="http://example.com/img/something/picture.webp" type="image/webp">' . "\n"
            . '<img src="http://example.com/img/something/picture.png" alt="alternate text">' . "\n"
            . '</picture>' . "\n";
        $this->assertSame($expected, $result);
    }
}
