<?php declare(strict_types=1);
namespace Netmatters\Images;

class ExtensionView
{
    public static function htmlSourceElement(string $imageUrl, Extension $extension): string
    {
        return "<source srcset=\"" . $imageUrl . $extension->getExtension() . "\""
            . " type=\"" . $extension->getPictureType() . "\">\n";
    }
}
