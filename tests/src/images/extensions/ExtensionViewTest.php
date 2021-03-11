<?php declare(strict_types=1);

use Netmatters\Images\Extensions\Extension;
use Netmatters\Images\Extensions\ExtensionView;
use PHPUnit\Framework\TestCase;

class ExtensionViewTest extends TestCase
{
    protected function createStubExtension($id, $extension, $pictureType): Extension
    {
        $stub = $this->createStub(Extension::class);
        $stub->method('getId')->willReturn($id);
        $stub->method('getExtension')->willReturn($extension);
        $stub->method('getPictureType')->willReturn($pictureType);
        return $stub;
    }

    public function testCreateHtmlSourceElement(): void
    {
        $extension = $this->createStubExtension(1, '.png', 'image/png');
        $actual = ExtensionView::htmlSourceElement('img/myPicture', $extension);
        $expected = '<source srcset="img/myPicture.png" type="image/png">' . "\n";
        $this->assertSame($expected, $actual);
    }

    public function testSourceElementUsesImageUrl(): void
    {
        $extension = $this->createStubExtension(1, '.png', 'image/png');
        $actual = ExtensionView::htmlSourceElement('some/path/to/image', $extension);
        $expected = '<source srcset="some/path/to/image.png" type="image/png">' . "\n";
        $this->assertSame($expected, $actual);
    }

    public function testSourceElementUsesExtension(): void
    {
        $extension = $this->createStubExtension(1, '.jpg', 'image/png');
        $actual = ExtensionView::htmlSourceElement('img/myPicture', $extension);
        $expected = '<source srcset="img/myPicture.jpg" type="image/png">' . "\n";
        $this->assertSame($expected, $actual);
    }

    public function testSourceElementUsesPictureType(): void
    {
        $extension = $this->createStubExtension(1, '.png', 'image/jpeg');
        $actual = ExtensionView::htmlSourceElement('img/myPicture', $extension);
        $expected = '<source srcset="img/myPicture.png" type="image/jpeg">' . "\n";
        $this->assertSame($expected, $actual);
    }

    public function testHtmlNotAffectedByExtensionId(): void
    {
        $extensionOne = $this->createStubExtension(1, '.png', 'image/png');
        $extensionTwo = $this->createStubExtension(2, '.png', 'image/png');
        $outputOne = ExtensionView::htmlSourceElement('img/myPicture', $extensionOne);
        $outputTwo = ExtensionView::htmlSourceElement('img/myPicture', $extensionTwo);
        $this->assertSame($outputOne, $outputTwo);
    }
}
