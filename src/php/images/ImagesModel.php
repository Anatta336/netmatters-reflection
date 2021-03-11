<?php declare(strict_types=1);
namespace Netmatters\Images;

use Netmatters\Database\DatabaseInterface;

class ImagesModel
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
                    image.imageUrl AS imageUrl,
                    extension.id AS extensionId,
                    extension.extension AS extension,
                    extension.pictureType AS pictureType,
                    imageHasExtension.isDefault AS isDefault
                FROM imageHasExtension
                JOIN image ON imageHasExtension.imageId = image.id
                JOIN extension ON imageHasExtension.extensionid = extension.id
                WHERE image.id = ?;";
        return $this->database->fetchResults($sql, $id);
    }

    public function getImageById(int $id): ?Image
    {
        $results = $this->getImageByIdAsArray($id);
        return $this->factory->createFromQueryResults($results);
    }
}
