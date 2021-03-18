<?php declare(strict_types=1);
namespace Netmatters\Images;

use Netmatters\Images\Extensions\ExtensionCollection;
use Netmatters\Images\Extensions\ExtensionFactory;
use Psr\Log\LoggerInterface;

class ImageFactory
{
    protected LoggerInterface $logger;
    protected ExtensionCollection $extensionCollection;

    function __construct(LoggerInterface $logger, ExtensionCollection $extensionCollection)
    {
        $this->logger = $logger;
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
        $extensionFactory = new ExtensionFactory($this->logger);

        $isFirst = true;
        $id = null;
        $imageUrl = null;
        $extensions = [];
        $defaultExtension = null;

        foreach ($results as $result) {
            if ($isFirst) {
                if (!isset($result['id']) || !is_numeric($result['id'])) {
                    $this->logger
                        ->error("Given input with no id in first result.", [$result]);
                    return null;
                }
                $id = (int)$result['id'];

                if (!isset($result['image_url']) || !is_string($result['image_url'])) {
                    $this->logger
                        ->error("Given input with no image_url in first result.", [$result]);
                    return null;
                }
                $imageUrl = $result['image_url'];

                $isFirst = false;
            }

            if (!isset($result['id']) || !is_numeric($result['id'])) {
                $this->logger->error("Given input with no id.", [$result]);
                return null;
            }
            if (((int)$result['id']) !== $id) {
                // multiple ids, these results aren't valid for creating a single image
                $this->logger->error("Given input with multiple ids.", [$results]);
                return null;
            }
            if (!isset($result['image_url']) || !is_string($result['image_url'])) {
                $this->logger->error("Given input with no image_url.", [$result]);
                return null;
            }
            if ($result['image_url'] !== $imageUrl) {
                // multiple imageUrls, these results aren't valid for creating a single image
                $this->logger->error("Given input with multiple image_urls.",
                    [$results]);
                return null;
            }

            if (!isset($result['extension_id']) || !is_numeric($result['extension_id'])) {
                $this->logger->error("Given input with no extension_id.", [$result]);
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
                    $this->logger->error("Didn't receive Extension from ExtensionFactory.",
                        [$result, $extensionFactory]);
                    return null;
                }

                // add extension to collection so it can be reused
                $this->extensionCollection->add($extension);
            }
            array_push($extensions, $extension);

            if (isset($result['is_default']) && $result['is_default'] && $defaultExtension !== null) {
                $this->logger->error("Multiple extensions are marked as default.",
                    [$result, $results, $extensions]);
                return null;
            } else if (isset($result['is_default']) && $result['is_default']) {
                $defaultExtension = $extension;
            }
        }

        if ($defaultExtension === null) {
            $this->logger->error("No extension is marked as default.",
                [$results, $extensions]);
            return null;
        }

        return new Image($id, $imageUrl, $extensions, $defaultExtension);
    }
}
