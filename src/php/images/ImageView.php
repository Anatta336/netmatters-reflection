<?php declare(strict_types=1);
namespace Netmatters\Images;

class ImageView
{
    public function pictureHtml(Image $image, string $urlStart, string $altText): string
    {
        $urlStart = htmlspecialchars($urlStart, ENT_QUOTES);
        $altText = htmlspecialchars($altText, ENT_QUOTES);

        $result = <<<"EOT"
        <picture>

        EOT;

        /** @var Extension $extension */
        foreach ($image->getExtensions() as $extension) {
            $result .= <<<"EOT"
                <source srcset="$urlStart{$image->getImageUrl()}.{$extension->getExtension()}" type="{$extension->getPictureType()}">

            EOT;
        }

        $result .= <<<"EOT"
            <img src="$urlStart{$image->getImageUrl()}.{$image->getDefaultExtension()->getExtension()}" alt="$altText">
        </picture>
        EOT;

        return $result;
    }
}
