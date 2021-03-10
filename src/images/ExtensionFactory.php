<?php declare(strict_types=1);
namespace Netmatters\Images;

class ExtensionFactory
{
    /**
     * @param array $results Associative array, as might be part of
     * what's returned from an SQL query. Example value:
     * [
     *     'extensionId => 2,
     *     'extension' => 'jpg',
     *     'pictureType' => 'image/jpeg',
     * ]
     * There may also be other items in the array, which will be ignored.
     * 
     * @return null|Extension null if extension couldn't be created,
     * otherwise a newly created Extension instance.
     */
    public function createFromQueryResult(array $result): ?Extension
    {
        if (!isset($result['extensionId'])
        || !isset($result['extension'])
        || !isset($result['pictureType'])) {
            // TODO: log warning
            return null;
        }

        return new Extension(
            (int)$result['extensionId'],
            $result['extension'],
            $result['pictureType'],
        );
    }
}
