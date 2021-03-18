<?php declare(strict_types=1);
namespace Netmatters\Images;

use Netmatters\Images\Extensions\ExtensionCollection;
use Netmatters\Images\Extensions\ExtensionFactory;

class ImageFactory
{
    private ExtensionCollection $extensionCollection;

    function __construct(ExtensionCollection $extensionCollection)
    {
        $this->extensionCollection = $extensionCollection;
    }

    /**
     * @param array $results Indexed array of associative arrays, as might be
     * returned from an SQL query. Should represent a single image, although
     * that image may have multiple extensions. Example value:
     * [
     *   [
     *      'id' => 1,
     *      'image_url' => 'img/one',
     *      'extension_id' => 2,
     *      'extension' => 'jpg',
     *      'picture_type' => 'image/jpeg',
     *      'is_default' => 1
     *   ],
     *   [
     *      'id' => 1,
     *      'image_url' => 'img/one',
     *      'extension_id' => 3,
     *      'extension' => 'webp',
     *      'picture_type' => 'image/webp',
     *      'is_default' => 0
     *   ],
     * ]
     * 
     * @return null|Image null if image couldn't be created, otherwise
     * a newly created Image instance.
     */
    public function createFromQueryResults(array $results): ?Image
    {
        $extensionFactory = new ExtensionFactory();

        $isFirst = true;
        $id = null;
        $imageUrl = null;
        $extensions = [];
        $defaultExtension = null;

        foreach ($results as $result) {
            if ($isFirst) {
                if (!isset($result['id']) || !is_numeric($result['id'])) {
                    // TODO: log a warning
                    echo "no integer id\n";
                    return null;
                }
                $id = (int)$result['id'];

                if (!isset($result['image_url']) || !is_string($result['image_url'])) {
                    // TODO: log a warning
                    echo "no imageUrl string\n";
                    return null;
                }
                $imageUrl = $result['image_url'];

                $isFirst = false;
            }

            if (!isset($result['id']) || !is_numeric($result['id'])) {
                //TODO: warning
                echo "no integer id\n";
                return null;
            }
            if (((int)$result['id']) !== $id) {
                // multiple ids, these results aren't valid for creating a single image
                // TODO: log a warning
                echo "multiple image ids in one set of results\n";
                return null;
            }
            if (!isset($result['image_url']) || !is_string($result['image_url'])) {
                //TODO: warning
                echo "no string image url";
                return null;
            }
            if ($result['image_url'] !== $imageUrl) {
                // multiple imageUrls, these results aren't valid for creating a single image
                // TODO: log a warning
                echo "multiple image URLs in one set of results\n";
                return null;
            }

            if (!isset($result['extension_id']) || !is_numeric($result['extension_id'])) {
                // TODO: log a warning, missing extension Id
                echo "missing extensionId\n";
                return null;
            }
            $extensionId = (int)$result['extension_id'];


            $extension = null;
            if ($this->extensionCollection->hasId($extensionId)) {
                // extension already exists in collection, so reuse it
                $extension = $this->extensionCollection->get($extensionId);
            } else {
                $extension = $extensionFactory->createFromQueryResult($result);
                if ($extension === null) {
                    // TODO: log a warning, unable to create extension
                    echo "extension couldn't be created\n";
                    return null;
                }

                // add extension to collection so it can be reused
                $this->extensionCollection->add($extension);
            }
            array_push($extensions, $extension);

            if (isset($result['is_default']) && $result['is_default'] && $defaultExtension !== null) {
                // TODO: log warning, multiple extensions marked as default
                echo "multiple default extensions\n";
                return null;
            } else if (isset($result['is_default']) && $result['is_default']) {
                $defaultExtension = $extension;
            }
        }

        if ($defaultExtension === null) {
            // TODO: log warning, no default extension for image
            echo "no default extension\n";
            return null;
        }

        return new Image($id, $imageUrl, $extensions, $defaultExtension);
    }
}
