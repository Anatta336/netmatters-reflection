<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\ImageView;
use Netmatters\Images\Image;
use PHPUnit\Framework\TestCase;

class ImageViewTest extends TestCase
{
    protected $jpgExtensionStub;
    protected $pngExtensionStub;
    protected $webpExtensionStub;

    protected function createStubExtension(string $extension, string $pictureType): Extension
    {
        $stub = $this->createStub(Extension::class);
        $stub->method('getExtension')->willReturn($extension);
        $stub->method('getPictureType')->willReturn($pictureType);
        return $stub;
    }

    protected function createStubImage(int $id, string $imageUrl,
        array $extensions, Extension $defaultExtension): Image
    {
        $stub = $this->createStub(Image::class);
        $stub->method('getId')->willReturn($id);
        $stub->method('getImageUrl')->willReturn($imageUrl);
        $stub->method('getExtensions')->willReturn($extensions);
        $stub->method('getDefaultExtension')->willReturn($defaultExtension);
        return $stub;
    }

    protected function setUp(): void
    {
        $this->jpgExtensionStub = $this->createStubExtension('jpg', 'image/jpeg');
        $this->pngExtensionStub = $this->createStubExtension('png', 'image/png');
        $this->webpExtensionStub = $this->createStubExtension('webp', 'image/webp');
    }

    public function testSingleExtension(): void
    {
        $imageStub = $this->createStubImage(
            123,
            'img/something/picture',
            [$this->jpgExtensionStub],
            $this->jpgExtensionStub,
        );

        $imageView = new ImageView();
        $result = $imageView->pictureHtml($imageStub, 'http://example.com/', 'alternate text');
        $expected = <<<'EOT'
        <picture>
            <source srcset="http://example.com/img/something/picture.jpg" type="image/jpeg">
            <img src="http://example.com/img/something/picture.jpg" alt="alternate text">
        </picture>
        EOT;
        $this->assertEquals($expected, $result);
    }

    public function testMultipleExtensions(): void
    {
        $imageStub = $this->createStubImage(
            123,
            'img/something/picture',
            [
                $this->jpgExtensionStub,
                $this->pngExtensionStub,
                $this->webpExtensionStub
            ],
            $this->jpgExtensionStub,
        );

        $imageView = new ImageView();
        $result = $imageView->pictureHtml($imageStub, 'http://example.com/', 'alternate text');
        $expected = <<<'EOT'
        <picture>
            <source srcset="http://example.com/img/something/picture.jpg" type="image/jpeg">
            <source srcset="http://example.com/img/something/picture.png" type="image/png">
            <source srcset="http://example.com/img/something/picture.webp" type="image/webp">
            <img src="http://example.com/img/something/picture.jpg" alt="alternate text">
        </picture>
        EOT;
        $this->assertEquals($expected, $result);
    }

    public function testZeroExtensions(): void
    {
        $imageStub = $this->createStubImage(
            123,
            'img/something/picture',
            [],
            $this->jpgExtensionStub,
        );

        $imageView = new ImageView();
        $result = $imageView->pictureHtml($imageStub, 'http://example.com/', 'alternate text');
        $expected = <<<'EOT'
        <picture>
            <img src="http://example.com/img/something/picture.jpg" alt="alternate text">
        </picture>
        EOT;
        $this->assertEquals($expected, $result);
    }

    public function testMultipleExtensionsAndDifferentDefault(): void
    {
        $imageStub = $this->createStubImage(
            123,
            'img/something/picture',
            [
                $this->jpgExtensionStub,
                $this->pngExtensionStub,
                $this->webpExtensionStub
            ],
            $this->pngExtensionStub,
        );

        $imageView = new ImageView();
        $result = $imageView->pictureHtml($imageStub, 'http://example.com/', 'alternate text');
        $expected = <<<'EOT'
        <picture>
            <source srcset="http://example.com/img/something/picture.jpg" type="image/jpeg">
            <source srcset="http://example.com/img/something/picture.png" type="image/png">
            <source srcset="http://example.com/img/something/picture.webp" type="image/webp">
            <img src="http://example.com/img/something/picture.png" alt="alternate text">
        </picture>
        EOT;
        $this->assertEquals($expected, $result);
    }
}
