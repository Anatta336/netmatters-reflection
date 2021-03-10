<?php declare(strict_types=1);
namespace Netmatters\Images;

class ExtensionCollection
{
    private array $extensions;

    function __construct()
    {
        $this->extensions = [];
    }

    /**
     * Adds the given extension to the collection. If there's already
     * an extension with that id stored, the stored extension is replaced.
     * @param Extension $extension
     * @return void
     */
    public function add(Extension $extension): void
    {
        $this->extensions[$extension->getId()] = $extension;
    }

    public function get(int $id): ?Extension
    {
        if (!$this->hasId($id)) {
            return null;
        }
        return $this->extensions[$id];
    }

    public function hasId(int $id): bool
    {
        return isset($this->extensions[$id])
            && ($this->extensions[$id] instanceof Extension);
    }

    /**
     * Checks if the exact Extension is already stored.
     * @param Extension $extension Extension to check for.
     * @return bool True if the exact Extension is already in collection.
     */
    public function has(Extension $extension): bool
    {
        $id = $extension->getId();
        if (!$this->hasId($id)) {
            return false;
        }
        
        return $this->extensions[$id] === $extension;
    }

    public function count(): int
    {
        return count($this->extensions);
    }
}
