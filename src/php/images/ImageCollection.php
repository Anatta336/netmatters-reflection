<?php declare(strict_types=1);
namespace Netmatters\Images;

class ImageCollection
{
    private array $images;

    function __construct()
    {
        $this->images = [];
    }

    /**
     * Adds the given image to the collection. If there's already
     * an image with that id stored, the stored image is replaced.
     * @param Image $image
     * @return void
     */
    public function add(Image $image): void
    {
        $this->images[$image->getId()] = $image;
    }

    public function get(int $id): ?Image
    {
        if (!$this->hasId($id)) {
            return null;
        }
        return $this->images[$id];
    }

    public function hasId(int $id): bool
    {
        return isset($this->images[$id])
            && ($this->images[$id] instanceof Image);
    }

    /**
     * Checks if the exact Image is already stored.
     * @param Image $image Image to check for.
     * @return bool True if the exact Image is already in collection.
     */
    public function has(Image $image): bool
    {
        $id = $image->getId();
        if (!$this->hasId($id)) {
            return false;
        }
        
        return $this->images[$id] === $image;
    }

    public function count(): int 
    {
        return count($this->images);
    }
}
