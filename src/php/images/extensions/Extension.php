<?php declare(strict_types=1);
namespace Netmatters\Images\Extensions;

/**
 * Immutable data object for an image extension.
 */
class Extension
{
    private int $id;
    private string $extension;
    private string $pictureType;

    function __construct(int $id, string $extension, string $pictureType)
    {
        $this->id = $id;
        $this->extension = htmlspecialchars($extension, ENT_QUOTES);
        $this->pictureType = htmlspecialchars($pictureType, ENT_QUOTES);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function getPictureType(): string
    {
        return $this->pictureType;
    }
}
