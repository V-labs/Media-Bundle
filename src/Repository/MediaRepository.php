<?php

namespace Vlabs\MediaBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Vlabs\MediaBundle\MediaInterface;

/**
 * MediaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MediaRepository extends EntityRepository
{
    /**
     * @param $mimeType
     * @return MediaInterface[]
     */
    public function findByMimeType($mimeType)
    {
        $qb = $this->_em->createQueryBuilder();

        return $qb
            ->select('m')
            ->from($this->_entityName, 'm')
            ->where($qb->expr()->like('m.mimeType', $qb->expr()->literal($mimeType)))
            ->addOrderBy('m.filename')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param array $mimeTypes
     * @return MediaInterface[]
     */
    public function findByMimeTypes(array $mimeTypes)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('m')
            ->from($this->_entityName, 'm');

        $orX = $qb->expr()->orX();

        foreach ($mimeTypes as $mimeType) {
            $orX->add($qb->expr()->like('m.mimeType', $qb->expr()->literal($mimeType)));
        }

        return $qb->where($orX)
            ->addOrderBy('m.filename')
            ->getQuery()
            ->getResult()
        ;
    }
}