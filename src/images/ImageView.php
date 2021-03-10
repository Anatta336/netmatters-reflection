<?php declare(strict_types=1);
namespace Netmatters\Images;

class ImageView
{
    public function pictureHtml(Image $image, string $urlStart, string $altText): string
    {
        $result = "<picture>";
        foreach ($image->getExtensions() as $extension) {
            $result .= "<source srcset=\""
                . $urlStart . $image->getImageUrl() . "." . $extension->getExtension()
                . "\" type=\"" . $extension->getPictureType() . "\">\n";
        }
        
        $defaultExt = $image->getDefaultExtension();
        $result .= "<img src=\""
                . $urlStart . $image->getImageUrl() . "." . $defaultExt->getExtension() . "\" "
                . "alt=\"" . $altText . "\">\n";
        $result .= "</picture>\n";

        return $result;
    }
}
