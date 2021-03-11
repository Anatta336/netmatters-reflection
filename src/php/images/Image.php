<?php declare(strict_types=1);
namespace Netmatters\Images;

use Netmatters\Images\Extensions\Extension;

/**
 * Immutable data object for an image available in one more more formats.
 */
class Image
{
    private int $id;
    private string $imageUrl;

    /**
     * @var array Array of Extension objects.
     */
    private array $extensions;

    private Extension $defaultExtension;

    function __construct(int $id, string $imageUrl,
        array $extensions, Extension $defaultExtension)
    {
        $this->id = $id;
        $this->imageUrl = htmlspecialchars($imageUrl, ENT_QUOTES);
        $this->extensions = $extensions;
        $this->defaultExtension = $defaultExtension;
    }
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getImageUrl(): string
    {
        return $this->imageUrl;
    }

    /**
     * @return array Array of Extensions
     */
    public function getExtensions(): array
    {
        return $this->extensions;
    }

    public function getDefaultExtension(): Extension
    {
        return $this->defaultExtension;
    }
}
