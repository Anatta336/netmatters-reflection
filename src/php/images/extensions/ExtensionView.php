<?php declare(strict_types=1);
namespace Netmatters\Images\Extensions;

class ExtensionView
{
    public static function htmlSourceElement(string $imageUrl, Extension $extension): string
    {
        $imageUrl = htmlentities($imageUrl, ENT_QUOTES);
        return '<source srcset="' . $imageUrl . $extension->getExtension() . '"'
            . ' type="' . $extension->getPictureType() . '">' . "\n";
    }
}
