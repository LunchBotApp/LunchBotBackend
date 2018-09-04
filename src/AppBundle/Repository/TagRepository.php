<?php


namespace AppBundle\Repository;

/**
 * Class TagRepository
 *
 * @package AppBundle\Repository
 */
class TagRepository extends BaseRepository
{
    /**
     * @param $extraction
     * @return array
     */
    public function getByExtraction($extraction)
    {
        return $this->findBy(['extraction' => $extraction]);
    }
}
