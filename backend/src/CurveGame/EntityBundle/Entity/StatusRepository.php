<?php

namespace CurveGame\EntityBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * StatusRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StatusRepository extends EntityRepository
{
    /**
     * Fetches a single status object by status name.
     *
     * @param string $name
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByName($name = "waiting") {

        $qb = $this->createQueryBuilder('s')
            ->where('s.name = :name')
            ->setParameter(':name', $name);

        $query = $qb->getQuery();
        $result = $query->getSingleResult();

        return $result;
    }
}
