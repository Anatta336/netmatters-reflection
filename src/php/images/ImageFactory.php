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
     *      'imageUrl' => 'img/one',
     *      'extensionId => 2,
     *      'extension' => 'jpg',
     *      'pictureType' => 'image/jpeg'
     *      'isDefault' => 1
     *   ],
     *   [
     *      'id' => 1,
     *      'imageUrl' => 'img/one',
     *      'extensionId => 3,
     *      'extension' => 'webp',
     *      'pictureType' => 'image/webp'
     *      'isDefault' => 0
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
                $id = (int)$result['id'];
                if (!is_int($id)) {
                    // TODO: log a warning
                    echo "id not int\n";
                    return null;
                }

                $imageUrl = $result['imageUrl'];
                if (!is_string($imageUrl)) {
                    // TODO: log a warning
                    echo "imageUrl not string\n";
                    return null;
                }

                $isFirst = false;
            }

            if (((int)$result['id']) !== $id) {
                // multiple ids, these results aren't valid for creating a single image
                // TODO: log a warning
                echo "multiple image ids in one set of results\n";
                return null;
            }
            if ($result['imageUrl'] !== $imageUrl) {
                // multiple imageUrls, these results aren't valid for creating a single image
                // TODO: log a warning
                echo "multiple image URLs in one set of results\n";
                return null;
            }

            $extensionId = (int)$result['extensionId'];
            if (!is_int($extensionId)) {
                // TODO: log a warning, missing extension Id
                echo "missing extensionId\n";
                return null;
            }


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

            if ($result['isDefault'] && $defaultExtension !== null) {
                // TODO: log warning, multiple extensions marked as default
                echo "multiple default extensions\n";
            } else if ($result['isDefault']) {
                $defaultExtension = $extension;
            }
        }

        if ($defaultExtension === null) {
            // TODO: log warning, no default extension for image
            echo "no default extension\n";
        }

        return new Image($id, $imageUrl, $extensions, $defaultExtension);
    }
}
