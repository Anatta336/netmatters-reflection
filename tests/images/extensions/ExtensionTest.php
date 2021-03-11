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

    public function testExtensionEscapesHtml(): void
    {
        $extension = new Extension(123, '<script>alert("boo!");</script>jpg', 'image/jpeg');
        $this->assertSame(
            '&lt;script&gt;alert(&quot;boo!&quot;);&lt;/script&gt;jpg',
            $extension->getExtension()
        );
    }
    public function testPictureTypeEscapesHtml(): void
    {
        $extension = new Extension(123, 'jpg', 'image/jpeg<script>alert("boo!");</script>');
        $this->assertSame(
            'image/jpeg&lt;script&gt;alert(&quot;boo!&quot;);&lt;/script&gt;',
            $extension->getPictureType()
        );
    }

    /*
        The result of ->getExtension() is likely to be used in an <img> element like:
            <img src='picture.$VALUE'>
        If $VALUE were:
            jpg' onload="alert('boo!');"
        The generated HTML would be:
            <img src='picture.jpg' onload="alert('boo!');"'>
        And the page would be vulnerable to XSS attacks.
    */
    public function testExtensionCannotBreakOutOfAttributeSingleQuotes(): void
    {
        $extension = new Extension(123, "jpg' onload=\"alert('boo!');\"", 'image/jpeg');
        $this->assertSame(
            "jpg&#039; onload=&quot;alert(&#039;boo!&#039;);&quot;",
            $extension->getExtension()
        );
    }
    public function testPictureTypeCannotBreakOutOfAttributeSingleQuotes(): void
    {
        $extension = new Extension(123, 'jpg', "image/jpeg' onload=\"alert('boo!');\"");
        $this->assertSame(
            "image/jpeg&#039; onload=&quot;alert(&#039;boo!&#039;);&quot;",
            $extension->getPictureType()
        );
    }

    /*
        Similar risk to that described above, but if value is inserted into:
            <img src="picture.$VALUE">
    */
    public function testExtensionCannotBreakOutOfAttributeDoubleQuotes(): void
    {
        $extension = new Extension(123, "jpg\" onload=\"alert('boo!');\"", 'image/jpeg');
        $this->assertSame(
            "jpg&quot; onload=&quot;alert(&#039;boo!&#039;);&quot;",
            $extension->getExtension()
        );
    }
    public function testPictureTypeCannotBreakOutOfAttributeDoubleQuotes(): void
    {
        $extension = new Extension(123, 'jpg', "image/jpeg\" onload=\"alert('boo!');\"");
        $this->assertSame(
            "image/jpeg&quot; onload=&quot;alert(&#039;boo!&#039;);&quot;",
            $extension->getPictureType()
        );
    }
}
