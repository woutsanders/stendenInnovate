<?php

namespace CurveGame\EntityBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PlayerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayerRepository extends EntityRepository {

    /**
     * Finds players by status name.
     *
     * @param string $status
     * @param string $orderBy
     * @return array|bool
     */
    public function findByStatus($status = 'waiting', $orderBy = 'ASC') {

        $qb = $this->createQueryBuilder('p')
            ->join('p.status', 's')
            ->addSelect('s')
            ->where('s.name = :statusName')
            ->orderBy('p.timestamp', $orderBy)
            ->setParameter('statusName', $status);

        $query = $qb->getQuery();
        $result = $query->getResult();

        // If status ready was passed and there are not 4 players, return false...
        if (count($result) === 4 && strtolower($status) === 'ready') {

            return $result;
        } else {

            return false;
        }
    }


    /**
     * @param null $userId
     * @return bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function findPositionInQueue($userId = null) {

        if (empty($userId)) return false;

        $checkQuery = "SELECT `Player`.`id` AS `playerId`,
                              `Status`.`name` AS `status`
                       FROM `Player`
                       JOIN `Status`
                       ON `Player`.`status_id`=`Status`.`id`
                       WHERE `Player`.`id` = :id
                       AND `Status`.`name`=\"waiting\"
                      ";

        $stmt = $this->getEntityManager()
            ->getConnection()
            ->prepare($checkQuery);
        $stmt->bindValue(':id', $userId);
        $stmt->execute();

        $result = $stmt->fetch();
        $result = $result['playerId'];

        if (empty($result) || !$result || !is_numeric($result)) {

            return false;
        } else {

            $query = "SELECT COUNT(`Player`.`id`) AS `position`
                      FROM `Player`
                      JOIN `Status`
                      ON `Player`.`status_id`=`Status`.`id`
                      WHERE `Player`.`id` <= :id
                      AND `Status`.`name`=\"waiting\"
                     ";

            $stmt = $this->getEntityManager()
                ->getConnection()
                ->prepare($query);
            $stmt->bindValue(':id', $userId);
            $stmt->execute();

            $result = $stmt->fetch();

            return $result['position'];
        }
    }
}