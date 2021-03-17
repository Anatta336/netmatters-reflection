<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\Extensions\ExtensionCollection;
use Netmatters\Images\ImageFactory;
use Netmatters\Images\Image;
use PHPUnit\Framework\TestCase;

class ImageFactoryTest extends TestCase
{
    protected ExtensionCollection $extensions;

    protected function createStubExtensionCollection(): ExtensionCollection
    {
        $stub = $this->createStub(ExtensionCollection::class);
        
        // for simplicity this stub always claims that an extension doesn't exist,
        // and so the 'get' method can always return null.
        $stub->method('hasId')->willReturn(false);
        $stub->method('get')->willReturn(null);
        $stub->method('add');
        return $stub;
    }

    protected function setUp(): void
    {
        $this->extensions = $this->createStubExtensionCollection();
    }

    public function testInstantiates(): void
    {
        $factory = new ImageFactory($this->extensions);
        $this->assertInstanceOf(ImageFactory::class, $factory);
    }

    public function testBuildFromArray(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 2,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertInstanceOf(Image::class, $image);
    }
    public function testBuiltImageHasUrl(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 2,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertSame('img/one', $image->getImageUrl());
    }

    public function testNoBuildIfIdNotInt(): void
    {
        $input = [
            [
                'id' => 'one',
                'image_url' => 'img/one',
                'extension_id' => 2,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 'one',
                'image_url' => 'img/one',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfSecondIdMissing(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 2,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'image_url' => 'img/one',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfIdMismatch(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 2,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 2,
                'image_url' => 'img/one',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfUrlNotString(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 3.14159,
                'extension_id' => 2,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 1,
                'image_url' => 3.14159,
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfUrlMissing(): void
    {
        $input = [
            [
                'id' => 1,
                'extension_id' => 2,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfSecondUrlMissing(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 2,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 1,
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfUrlMismatch(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 2,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 1,
                'image_url' => 'img/two',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfExtensionIdNotInt(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 'jpg',
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 1,
                'image_url' => 'img/two',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfExtensionIdMissing(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 1,
                'image_url' => 'img/two',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testWillReuseExtension(): void
    {
        $extensions = $this->createMock(ExtensionCollection::class);
        $extensions->method('hasId')->will($this->returnCallback(function($id)
        {
            return $id === 1;
        }));
        
        $extensions->method('get')->will($this->returnCallback(function($id)
        {
            if ($id === 1) {
                return new Extension(1, 'jpg', 'image/jpeg');
            }
            return null;
        }));
        
        // the factory shouldn't try to add a new extension, as it can reuse the existing
        $extensions->expects($this->never())
            ->method('add');

        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 1,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ]
        ];
        $factory = new ImageFactory($extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertInstanceOf(Image::class, $image);
    }

    public function testNoBuildIfExtensionPictureTypeMissing(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 1,
                'extension' => 'jpg',
                // 'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfMultipleDefaultExtensions(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 1,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 1,
            ],
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 1,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }

    public function testNoBuildIfNoDefaultExtension(): void
    {
        $input = [
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 1,
                'extension' => 'jpg',
                'picture_type' => 'image/jpeg',
                'is_default' => 0,
            ],
            [
                'id' => 1,
                'image_url' => 'img/one',
                'extension_id' => 3,
                'extension' => 'webp',
                'picture_type' => 'image/webp',
                'is_default' => 0,
            ],
        ];
        $factory = new ImageFactory($this->extensions);
        $image = $factory->createFromQueryResults($input);
        $this->assertNull($image);
    }
}
