<?php declare(strict_types=1);
namespace Netmatters\Images;

use Netmatters\Database\DatabaseInterface;

class ImageStore
{
    protected DatabaseInterface $database;
    protected ImageFactory $factory;

    function __construct(
        DatabaseInterface $database,
        ImageFactory $imageFactory)
    {
        $this->database = $database;
        $this->factory = $imageFactory;
    }

    protected function getImageByIdAsArray($id): array
    {
        $sql = "SELECT image.id AS id,
                    image.image_url AS image_url,
                    extension.id AS extension_id,
                    extension.extension AS extension,
                    extension.picture_type AS picture_type,
                    image_has_extension.is_default AS is_default
                FROM image_has_extension
                JOIN image ON image_has_extension.image_id = image.id
                JOIN extension ON image_has_extension.extension_id = extension.id
                WHERE image.id = ?;";
        return $this->database->fetchResults($sql, $id);
    }

    public function getImageById(int $id): ?Image
    {
        $results = $this->getImageByIdAsArray($id);
        return $this->factory->createFromQueryResults($results);
    }
}
