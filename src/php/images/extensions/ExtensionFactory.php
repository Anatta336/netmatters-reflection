<?php declare(strict_types=1);
namespace Netmatters\Images\Extensions;

use Psr\Log\LoggerInterface;

class ExtensionFactory
{
    protected LoggerInterface $logger;

    function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $results Associative array, as might be part of
     * what's returned from an SQL query. Example value:
     * [
     *     'extension_id' => 2,
     *     'extension' => 'jpg',
     *     'picture_type' => 'image/jpeg',
     * ]
     * There may also be other items in the array, which will be ignored.
     * 
     * @return null|Extension null if extension couldn't be created,
     * otherwise a newly created Extension instance.
     */
    public function createFromQueryResult(array $result): ?Extension
    {
        if (!isset($result['extension_id'])
        || !is_numeric($result['extension_id'])
        || !isset($result['extension'])
        || !isset($result['picture_type'])) {
            $this->logger->error("Unable to create Extension from array.",
                [$result]);
            return null;
        }
        
        return new Extension(
            (int)$result['extension_id'],
            $result['extension'],
            $result['picture_type'],
        );
    }
}
